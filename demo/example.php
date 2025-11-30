<?php
$config = [
'api_url' => 'https://api.example.com',
'timeout' => 5000,
'retries' => 3,
'headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Bearer token123'],
];
function fetchData($endpoint, $options = []) {
global $config;
$url = $config['api_url'] . '/' . $endpoint;
$defaultOptions = array_merge(['method' => 'GET', 'headers' => $config['headers']], $options);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $defaultOptions['headers']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($httpCode !== 200) {
throw new Exception("HTTP error! status: $httpCode");
}
return json_decode($response, true);
}
function processUsers($users) {
$activeUsers = array_filter($users, function ($user) {
return $user['active'] ?? false;
});
$mappedUsers = array_map(function ($user) {
return [
'id' => $user['id'],
'name' => $user['firstName'] . ' ' . $user['lastName'],
'email' => strtolower($user['email']),
'role' => $user['role'] ?? 'user',
];
}, $activeUsers);
usort($mappedUsers, function ($a, $b) {
return strcmp($a['name'], $b['name']);
});
return $mappedUsers;
}
class DataManager {
private $data = [];
private $observers = [];
public function __construct($initialData = []) {
$this->data = $initialData;
}
public function addItem($item) {
$this->data[] = $item;
$this->notify();
}
public function removeItem($id) {
$this->data = array_filter($this->data, function ($item) use ($id) {
return $item['id'] !== $id;
});
$this->notify();
}
public function updateItem($id, $updates) {
foreach ($this->data as $key => $item) {
if ($item['id'] === $id) {
$this->data[$key] = array_merge($item, $updates);
$this->notify();
break;
}
}
}
public function subscribe($callback) {
$this->observers[] = $callback;
}
private function notify() {
foreach ($this->observers as $callback) {
$callback($this->data);
}
}
}
function debounce($func, $delay) {
static $lastCall = null;
return function (...$args) use ($func, $delay, &$lastCall) {
$now = microtime(true);
if ($lastCall === null || $now - $lastCall >= $delay / 1000) {
$lastCall = $now;
return $func(...$args);
}
};
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$formData = [
'name' => $_POST['name'] ?? '',
'email' => $_POST['email'] ?? '',
'message' => $_POST['message'] ?? '',
];
try {
$result = fetchData('submit', ['method' => 'POST', 'body' => json_encode($formData)]);
echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
}
$manager = new DataManager([['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']]);
$manager->subscribe(function ($data) {
error_log('Data updated: ' . json_encode($data));
});
$manager->addItem(['id' => 3, 'name' => 'Bob']);
$manager->updateItem(1, ['name' => 'John Doe']);
$manager->removeItem(2);
add_action('init', function () {
register_post_type('project', [
'labels' => ['name' => 'Projects', 'singular_name' => 'Project'],
'public' => true,
'has_archive' => true,
'supports' => ['title', 'editor', 'thumbnail'],
'menu_icon' => 'dashicons-portfolio',
]);
});
add_filter('the_content', function ($content) {
if (is_singular('project')) {
$custom_field = get_field('project_details');
if ($custom_field) {
$content .= '<div class="project-details">' . esc_html($custom_field) . '</div>';
}
}
return $content;
});
function get_featured_projects($count = 5) {
$args = [
'post_type' => 'project',
'posts_per_page' => $count,
'meta_key' => 'featured',
'meta_value' => '1',
'orderby' => 'date',
'order' => 'DESC',
];
$query = new WP_Query($args);
$projects = [];
if ($query->have_posts()) {
while ($query->have_posts()) {
$query->the_post();
$projects[] = ['id' => get_the_ID(), 'title' => get_the_title(), 'url' => get_permalink()];
}
wp_reset_postdata();
}
return $projects;
}
add_shortcode('project_list', function ($atts) {
$atts = shortcode_atts(['category' => '', 'count' => 10], $atts);
$args = ['post_type' => 'project', 'posts_per_page' => intval($atts['count'])];
if (!empty($atts['category'])) {
$args['tax_query'] = [['taxonomy' => 'project_category', 'field' => 'slug', 'terms' => $atts['category']]];
}
$query = new WP_Query($args);
$output = '<div class="project-list">';
if ($query->have_posts()) {
while ($query->have_posts()) {
$query->the_post();
$output .= '<div class="project-item">';
$output .= '<h3>' . get_the_title() . '</h3>';
$output .= '<div class="project-excerpt">' . get_the_excerpt() . '</div>';
$output .= '</div>';
}
wp_reset_postdata();
} else {
$output .= '<p>No projects found.</p>';
}
$output .= '</div>';
return $output;
});
add_action('admin_notices', function () {
if (get_transient('project_import_success')) {
echo '<div class="notice notice-success is-dismissible"><p>Projects imported successfully!</p></div>';
delete_transient('project_import_success');
}
});

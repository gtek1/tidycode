const config = {
apiUrl: 'https://api.example.com',
timeout: 5000,
retries: 3,
headers: { 'Content-Type': 'application/json', Authorization: 'Bearer token123' },
};
function fetchData(endpoint, options = {}) {
const url = `${config.apiUrl}/${endpoint}`;
const defaultOptions = { method: 'GET', headers: config.headers, ...options };
return fetch(url, defaultOptions)
.then(response => {
if (!response.ok) {
throw new Error(`HTTP error! status: ${response.status}`);
}
return response.json();
})
.catch(error => {
console.error('Fetch error:', error);
throw error;
});
}
const processUsers = users => {
return users
.filter(user => user.active)
.map(user => ({
id: user.id,
name: user.firstName + ' ' + user.lastName,
email: user.email.toLowerCase(),
role: user.role || 'user',
}))
.sort((a, b) => a.name.localeCompare(b.name));
};
class DataManager {
constructor(initialData = []) {
this.data = initialData;
this.observers = [];
}
addItem(item) {
this.data.push(item);
this.notify();
}
removeItem(id) {
this.data = this.data.filter(item => item.id !== id);
this.notify();
}
updateItem(id, updates) {
const index = this.data.findIndex(item => item.id === id);
if (index !== -1) {
this.data[index] = { ...this.data[index], ...updates };
this.notify();
}
}
subscribe(callback) {
this.observers.push(callback);
}
notify() {
this.observers.forEach(callback => callback(this.data));
}
}
const debounce = (func, delay) => {
let timeoutId;
return (...args) => {
clearTimeout(timeoutId);
timeoutId = setTimeout(() => func(...args), delay);
};
};
document.addEventListener('DOMContentLoaded', () => {
const buttons = document.querySelectorAll('.button');
buttons.forEach(button => {
button.addEventListener('click', e => {
e.preventDefault();
const action = button.dataset.action;
if (action === 'submit') {
handleSubmit();
} else if (action === 'cancel') {
handleCancel();
}
});
});
});
async function handleSubmit() {
const formData = {
name: document.querySelector('#name').value,
email: document.querySelector('#email').value,
message: document.querySelector('#message').value,
};
try {
const result = await fetchData('submit', {
method: 'POST',
body: JSON.stringify(formData),
});
console.log('Success:', result);
alert('Form submitted successfully!');
} catch (error) {
console.error('Submission failed:', error);
alert('Failed to submit form. Please try again.');
}
}

# Tidy Code Auto Formatter

Write code. Press save. Always perfect formatting on save. No decisions, no effort, no exceptions.

## Features

- ✅ Format on save with Prettier
- ✅ 4-space indentation (no tabs, no auto-detection)
- ✅ Minimal blank lines for faster reading
- ✅ All editor sounds muted
- ✅ No comments policy (clean, self-documenting code)
- ✅ AI coding assistant rules included (Cursor, Claude Code)
- ✅ PHP 8.2+ support via Prettier plugin

## Supported Languages

- JavaScript / TypeScript
- HTML
- CSS / SCSS
- JSON
- Markdown
- PHP (8.2+)

## Quick Setup

### New Project

```bash
npm install
```

That's it! Format-on-save will work automatically in VS Code.

### Merging Into Existing Project

If you want to add this formatting setup to an existing project, follow these steps:

**1. Clean Up Optional Files**

First, remove the optional files you don't need:

```bash
npm run cleanup
```

This removes: **`.cursorrules`**, **`CLAUDE.md`**, **`.editorconfig`**, **`demo/`**

**2. Copy Core Files**

Copy these essential files to your project:

- **`.vscode/settings.json`** - Merge with your existing VS Code settings (or replace if you want these exact settings)
- **`.prettierrc`** - Copy directly (or merge if you have existing Prettier config)
- **`.prettierignore`** - Merge with your existing ignore patterns if you have them

**3. Update `package.json`**

Add to your **`devDependencies`**:

```json
"prettier": "^3.1.0",
"@prettier/plugin-php": "^0.22.2"
```

Optionally add these scripts:

```json
"format": "prettier --write \"**/*.{js,ts,jsx,tsx,html,css,scss,json,md,php}\"",
"format:check": "prettier --check \"**/*.{js,ts,jsx,tsx,html,css,scss,json,md,php}\""
```

**4. Install Dependencies**

```bash
npm install
```

**5. Format Your Project**

```bash
npm run format
```

## Usage

Auto-formatting happens on save by default. To run formatting manually, use these commands:

```bash
npm run format          # Format all files
npm run format:check    # Check formatting without changes
```

## Core Files (Required for Formatting)

### `.vscode/settings.json`

- Enables format-on-save
- Sets 4-space indentation
- Disables auto-detection
- Mutes all editor sounds
- Assigns Prettier as default formatter for all supported languages

### `.prettierrc`

- 4-space tabs, 120-char line width
- Single quotes, trailing commas
- PHP plugin handling in **`overrides`** section (prevents conflicts)

### `.prettierignore`

- Excludes **`node_modules`**, **`vendor`**, minified files
- Skips build outputs and cache directories

### `package.json`

- Prettier and PHP plugin as dev dependencies
- npm scripts for formatting (**`format`**, **`format:check`**)
- npm script for cleanup (**`cleanup`**)

## Optional Files

### `.cursorrules`

- Cursor AI behavior rules
- Only needed if using Cursor AI editor

### `CLAUDE.md`

- Claude Code behavior rules (official format)
- Only needed if using Claude Code assistant

### `.editorconfig`

- Universal editor config for non-VS Code editors
- Only needed if team uses Sublime, Vim, or similar editors

### `demo/`

- Sample files demonstrating formatting across all supported languages
- Includes WordPress PHP examples (hooks, custom post types, shortcodes)
- **Delete before deploying** - these are only for testing the formatter

## Disabling Auto-Formatting

If you need to temporarily or permanently disable formatting, use one of these methods:

**Temporary (recommended for quick toggle):**

In **`.vscode/settings.json`**, change:

```json
"editor.formatOnSave": true,
```

to:

```json
"editor.formatOnSave": false,
```

**Permanent (disable Prettier entirely):**

In **`.vscode/settings.json`**, add:

```json
"prettier.enable": false,
```

**Per-file basis:**

Add specific files or patterns to **`.prettierignore`**:

```
# Disable for specific files
legacy-code.js
vendor/**/*.php
```

## Disabling PHP Support

If you don't need PHP formatting support, choose one of these options:

**Option 1: Complete removal (recommended if not using PHP)**

Remove the entire **`overrides`** array from **`.prettierrc`**, then uninstall the PHP plugin:

```bash
npm uninstall @prettier/plugin-php
```

**Option 2: Keep plugin, skip PHP files**

Add to **`.prettierignore`**:

```
*.php
```

# Role Permissions Picker — Design Spec

**Date:** 2026-06-23  
**Scope:** Fix empty permission rows on `/roles/{id}/edit` and `/roles/create`

## Problem

Group headers (e.g. الواجبات) render with counts, but permission rows inside open groups show as blank stripes without Arabic or English text.

## Root Cause

Incremental CSS patches in `admin-pages.css` fought Valex + Bootstrap RTL:

- `.form-check` uses float + negative margin in RTL
- Custom `display: flex` on labels collapsed text width to zero
- Theme CSS variables and `.admin-form` context amplified conflicts

Backend is correct: `Permissions::groupedForPicker()` returns 23 groups / 275 permissions with Arabic labels from `config/permissions.php`.

## Solution

Rebuild as an isolated component:

| Layer | Approach |
|-------|----------|
| HTML | `<details>/<summary>` accordion, no Bootstrap form-check |
| CSS | New `role-permissions.css`, all rules under `#rolePermissionsPicker` |
| JS | Simplified `initPermissionPicker()` — search, select all, expand/collapse |
| Assets | `@push('styles')` with `filemtime` cache bust |

## Display

Each permission shows:

1. Arabic label (primary)
2. English key below (e.g. `assignment-list`)

## Files

- `resources/views/admin/partials/role-permissions-picker.blade.php` — rewritten
- `public/assets/css/role-permissions.css` — new
- `public/assets/js/admin-tables.js` — `initPermissionPicker` updated
- `public/assets/css/admin-pages.css` — old Permission Picker section removed

No changes to `RoleController`, `Permissions.php`, or `config/permissions.php`.

## Verification

1. Render partial contains Arabic labels and permission keys
2. Browser: open group shows readable rows
3. Search filters correctly
4. Save role still syncs permissions
5. Network tab loads `role-permissions.css` with fresh `filemtime` query string

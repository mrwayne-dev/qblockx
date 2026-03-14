# Plan 01 — Design System
**Status:** Pending
**Execution Order:** 1 (must complete before all other plans)

## Goal
Replace the white/blue ArqoraCapital theme with CrestVale Bank's dark fintech palette and swap DM Sans → Recoleta DEMO.

## Files to Edit
| File | Change |
|------|--------|
| `assets/css/main.css` | @font-face, :root color/font variables, gradients |
| `includes/head.php` | Font preload links |
| `assets/css/admin/admin.css` | Audit & fix hardcoded colors |
| `assets/css/user/user.css` | Audit & fix hardcoded colors |
| `assets/css/admin/admin-responsive.css` | Responsive audit |
| `assets/css/user/user-responsive.css` | Responsive audit |
| `assets/js/light-rays.js` | Update WebGL colors to emerald |

## New Color Palette
| Variable | Value | Role |
|----------|-------|------|
| `--color-bg` | `#0F1115` | Fintech Black — page background |
| `--color-surface` | `#1A1D23` | Graphite — cards, panels |
| `--color-surface-2` | `#1E222A` | Slightly lighter surface |
| `--color-surface-3` | `#22272F` | Hover/active surface |
| `--color-accent` | `#3FE0A1` | Emerald — primary accent |
| `--color-accent-dark` | `#2BC98A` | Emerald dark for hover states |
| `--color-accent-glow` | `rgba(63, 224, 161, 0.15)` | Glow effect |
| `--color-text` | `#E6E8EC` | Silver — primary text |
| `--color-text-muted` | `rgba(230, 232, 236, 0.55)` | Muted text |
| `--color-text-faint` | `rgba(230, 232, 236, 0.35)` | Faint/placeholder text |
| `--color-border` | `rgba(255, 255, 255, 0.07)` | Subtle borders |
| `--color-border-hover` | `rgba(255, 255, 255, 0.14)` | Border on hover |

Keep unchanged: `--color-success: #10B981`, `--color-warning: #F59E0B`, `--color-error: #EF4444`

## New Font
- Family: `'Recoleta DEMO'`
- Files already in `assets/fonts/`:
  - `Recoleta-RegularDEMO.woff2`
  - `Recoleta-RegularDEMO.woff`
- Replace `--font-display` and `--font-body` values

## Updated Gradients
```css
--gradient-accent:      linear-gradient(135deg, #3FE0A1, #2BC98A);
--gradient-surface:     linear-gradient(160deg, #0F1115 0%, #1A1D23 100%);
--gradient-card-hover:  linear-gradient(135deg, rgba(63,224,161,0.06), rgba(43,201,138,0.03));
```

## Verification
- [ ] Homepage loads with dark background (#0F1115)
- [ ] Cards render with graphite (#1A1D23)
- [ ] Accent buttons are emerald (#3FE0A1)
- [ ] Text is silver (#E6E8EC)
- [ ] Recoleta font renders (check browser DevTools → Network → Fonts)
- [ ] No 404s for font files in browser console

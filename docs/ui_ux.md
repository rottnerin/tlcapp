# TLC 2.0 UI/UX Reference

Design system reference for porting to React Native iOS/Android.

---

## Colors

### Brand Palette

| Name | Hex | Usage |
|------|-----|-------|
| Navy | `#0d3b66` | Headers, navigation, primary text |
| Cream | `#faf0ca` | Background |
| Gold | `#f4d35e` | Accents, active states, highlights |
| Orange | `#ee964b` | Primary CTA buttons, links |

### Feature Colors

| Feature | Hex |
|---------|-----|
| Wellness Teal | `#004643` |
| Success Green | `#10b981` |
| Error Red | `#ef4444` |
| Warning Yellow | `#f59e0b` |
| Gray Text | `#4b5563` / `#6b7280` |

### Division Accents

| Division | Color |
|----------|-------|
| Elementary (ES) | Gold `#f4d35e` |
| Middle (MS) | Orange `#ee964b` |
| High (HS) | Navy `#0d3b66` |

### Gradients

```javascript
// Navy (headers, navigation)
['#0d3b66', '#164773']

// Orange (buttons)
['#ee964b', '#d97706']

// Gold (sub-nav, highlights)
['#f4d35e', 'rgba(244, 211, 94, 0.9)']

// Wellness Teal
['#004643', '#005A56']

// Success (joined state)
['#10b981', '#059669']
```

---

## Typography

**Font:** Lexend (Google Fonts)  
**Weights:** 400, 500, 600, 700

| Style | Size | Weight |
|-------|------|--------|
| H1 | 30px | 700 |
| H2 | 24px | 700 |
| H3 | 20px | 600 |
| Body | 16px | 400 |
| Small | 14px | 400 |
| Caption | 12px | 500 |

**Note:** Minimum 16px for inputs (prevents iOS zoom).

---

## Spacing & Layout

| Token | Value |
|-------|-------|
| xs | 4px |
| sm | 8px |
| md | 12px |
| lg | 16px |
| xl | 24px |
| 2xl | 32px |

**Border Radius:**
- Cards: 16px
- Buttons: 8px
- Badges/Tags: 9999px (pill)
- Inputs: 8px

**Shadows:** Navy-tinted (`#0d3b66`) with 0.08-0.15 opacity

---

## Components

### Navigation

```javascript
// Top bar
{ backgroundColor: '#0d3b66', height: 64 }

// Active nav item
{ backgroundColor: '#f4d35e', color: '#0d3b66' }

// Sub-navigation bar
{ background: goldGradient, borderBottom: '3px solid #ee964b' }
```

### Session Cards

```javascript
// Container
{ backgroundColor: '#fff', borderRadius: 16, overflow: 'hidden' }

// Header (Wellness)
{ background: ['#004643', '#005A56'], padding: 20 }

// Header (CCL)  
{ background: ['#0d3b66', '#164773'], padding: 20 }

// Body
{ padding: 20, backgroundColor: '#fff' }

// Footer
{ padding: 16, backgroundColor: '#f9fafb', borderTop: '#e5e7eb' }

// Joined state
{ border: '4px solid #10b981', transform: scale(1.02) }

// Joined badge (overlay)
{ position: 'absolute', top: 12, right: 12, backgroundColor: '#fff', 
  color: '#059669', padding: '8px 16px', borderRadius: 8, fontWeight: 800 }
```

### Buttons

```javascript
// Primary (Orange)
{ background: ['#ee964b', '#d97706'], color: '#fff', 
  padding: '12px 24px', borderRadius: 8, fontWeight: 600 }

// Secondary (Navy)
{ backgroundColor: '#0d3b66', color: '#fff' }

// Join (Wellness)
{ background: ['#004643', '#005A56'], border: '2px solid #004643' }

// Enrolled (disabled)
{ backgroundColor: '#10b981', color: '#fff' }

// Full (disabled)
{ backgroundColor: '#ef4444', color: '#fff' }

// Remove/Unjoin
{ backgroundColor: '#fee2e2', color: '#dc2626' }
```

### Inputs

```javascript
{ backgroundColor: '#fff', color: '#111827', border: '1px solid #d1d5db',
  borderRadius: 8, padding: '12px 16px', fontSize: 16 }

// Focus: borderColor: '#0d3b66'
// Error: borderColor: '#ef4444'
```

### Tags/Badges

```javascript
// Division tags
{ paddingVertical: 4, paddingHorizontal: 12, borderRadius: 9999, fontSize: 12 }
// ES: { backgroundColor: 'rgba(244, 211, 94, 0.3)', color: '#0d3b66' }
// MS: { backgroundColor: 'rgba(238, 150, 75, 0.2)', color: '#0d3b66' }
// HS: { backgroundColor: 'rgba(13, 59, 102, 0.15)', color: '#0d3b66' }

// Status badges
// Full: { backgroundColor: '#ef4444', color: '#fff' }
// Filling: { backgroundColor: '#f59e0b', color: '#fff' }
```

### Progress Bar

```javascript
{ height: 8, backgroundColor: 'rgba(255,255,255,0.2)', borderRadius: 4 }
// Fill colors by percentage:
// < 75%: '#10b981'
// >= 75%: '#f59e0b'  
// 100%: '#ef4444'
```

### Alerts

```javascript
// Success
{ backgroundColor: '#f0fdf4', border: '1px solid #bbf7d0', color: '#166534' }

// Error
{ backgroundColor: '#fef2f2', border: '1px solid #fecaca', color: '#991b1b' }
```

---

## Icons

**Library:** Font Awesome 6

| Icon | Usage |
|------|-------|
| `bookmark` | My PL |
| `calendar-alt` | Schedule/Dates |
| `clock` | Time |
| `user-tie` | Presenter |
| `users` | Enrollment |
| `map-marker-alt` | Location |
| `heart` | Wellness |
| `chalkboard-teacher` | CCL |
| `check` | Enrolled |
| `times` | Full/Remove |
| `user-plus` | Join |
| `eye` | View Details |
| `print` | Print |
| `external-link-alt` | External link |

**Category Emojis:** 🎨 Arts, 🏃 Sports, 💃 Dance, 🌍 Culture, 🧘 Yoga, 💚 Health, 🌿 Default

---

## Mobile Specifics

- **Touch targets:** 44px minimum height
- **Safe areas:** Support notched devices (bottom padding)
- **Font:** 16px minimum on inputs
- **Sticky navigation** on scroll

---

## Screens

### User
1. **Dashboard** - Stats cards, session overview
2. **Schedule** - Day selector, division filter, event list
3. **Wellness** - Card grid with join/enrolled states
4. **CCL** - Sessions grouped by time slot
5. **My PL** - Personal session list with stats

### Admin
1. Dashboard, PL Days, Wellness, CCL, Schedule, Users, Reports (CRUD interfaces)

---

## Empty States

```javascript
{ textAlign: 'center', padding: 48, backgroundColor: '#fff', borderRadius: 16 }
// Emoji (48px): 📚 My PL, 🌿 Wellness, 👨‍🏫 CCL, 📅 Schedule
// Title: fontSize 20, fontWeight 600, color '#374151'
// Description: color '#6b7280'
```

---

## React Native Libraries

- **Navigation:** @react-navigation/native, bottom-tabs
- **Gradients:** expo-linear-gradient
- **Icons:** @expo/vector-icons (FontAwesome)
- **Fonts:** expo-font (Lexend)
- **Safe Areas:** react-native-safe-area-context
- **Auth:** expo-auth-session (Google OAuth)

---

## Assets

| Asset | URL/Path |
|-------|----------|
| AES Logo | https://visitors.aes.ac.in/images/aes.png |
| Local Logo | `/public/logos/AES_GoldLogo.jpg` |
| Wellbeing | `/public/logos/wellbeing.png` |

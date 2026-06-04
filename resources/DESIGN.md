---
name: Obsidian Prism
colors:
  surface: '#0b1326'
  surface-dim: '#0b1326'
  surface-bright: '#31394d'
  surface-container-lowest: '#060e20'
  surface-container-low: '#131b2e'
  surface-container: '#171f33'
  surface-container-high: '#222a3d'
  surface-container-highest: '#2d3449'
  on-surface: '#dae2fd'
  on-surface-variant: '#c7c4d7'
  inverse-surface: '#dae2fd'
  inverse-on-surface: '#283044'
  outline: '#908fa0'
  outline-variant: '#464554'
  surface-tint: '#c0c1ff'
  primary: '#c0c1ff'
  on-primary: '#1000a9'
  primary-container: '#8083ff'
  on-primary-container: '#0d0096'
  inverse-primary: '#494bd6'
  secondary: '#ddb7ff'
  on-secondary: '#490080'
  secondary-container: '#6f00be'
  on-secondary-container: '#d6a9ff'
  tertiary: '#4edea3'
  on-tertiary: '#003824'
  tertiary-container: '#00885d'
  on-tertiary-container: '#000703'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#e1e0ff'
  primary-fixed-dim: '#c0c1ff'
  on-primary-fixed: '#07006c'
  on-primary-fixed-variant: '#2f2ebe'
  secondary-fixed: '#f0dbff'
  secondary-fixed-dim: '#ddb7ff'
  on-secondary-fixed: '#2c0051'
  on-secondary-fixed-variant: '#6900b3'
  tertiary-fixed: '#6ffbbe'
  tertiary-fixed-dim: '#4edea3'
  on-tertiary-fixed: '#002113'
  on-tertiary-fixed-variant: '#005236'
  background: '#0b1326'
  on-background: '#dae2fd'
  surface-variant: '#2d3449'
typography:
  display-lg:
    fontFamily: Outfit
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Outfit
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Outfit
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Outfit
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  title-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  mono-data:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  container-max: 1440px
  gutter: 24px
  margin-desktop: 40px
  margin-mobile: 16px
  stack-sm: 8px
  stack-md: 16px
  stack-lg: 32px
---

## Brand & Style

This design system is engineered for the next generation of enterprise financial management. It fuses the analytical rigor of an ERP with the immersive, forward-leaning aesthetic of **Glassmorphism**. The target audience—CFOs, financial analysts, and fintech operators—requires a high-density information environment that feels expansive rather than claustrophobic.

The visual narrative centers on "depth through transparency." By utilizing layered frosted surfaces against a deep obsidian void, the UI establishes a clear cognitive hierarchy. The emotional response is one of sophisticated control: professional, high-tech, and fluid. The interface should feel like a premium command center where data floats in a structured, multi-dimensional space.

## Colors

The palette is rooted in **Deep Obsidian (#0f172a)**, providing a high-contrast foundation for vibrant data visualization. 

- **Primary Gradient**: A kinetic transition from **Vibrant Indigo (#6366f1)** to **Deep Purple (#a855f7)**. Use this for primary actions, active states, and key data trends.
- **Functional Colors**: **Soft Emerald (#10b981)** denotes growth and fiscal health, while **Soft Rose (#f43f5e)** identifies deficits and risks.
- **Surface Strategy**: Unlike traditional flat UIs, surfaces are defined by their transparency. Use the `glass_fill` with a `backdrop-filter: blur(12px)` to create the signature frosted effect. Every glass container must be capped with a 1px `glass_stroke` to ensure edge definition against the dark background.

## Typography

This design system employs a dual-font strategy to balance character with utility. 

**Outfit** is the display typeface, used for headlines and large currency figures. Its geometric construction mirrors the "modern tech" brand identity. **Inter** handles all body copy, inputs, and dense UI labels, providing industry-leading legibility for financial data.

For raw financial figures and ledger entries, use **JetBrains Mono** at the `mono-data` level to ensure character alignment and rapid scanning of numerical columns. Maintain tight tracking on display styles to keep the interface feeling compact and professional.

## Layout & Spacing

The layout utilizes a **12-column fluid grid** for the main content area, with a fixed-width side navigation (280px). 

- **Desktop**: Content is housed in a centered container with 40px outer margins. Use a 24px gutter to provide breathing room between glass cards.
- **Tablet**: Transition to an 8-column grid; the side navigation collapses into a floating glass bar or hamburger menu.
- **Mobile**: A single-column flow with 16px margins. Data cards should span the full width of the screen.

Vertical rhythm is governed by an 8px base unit. Components should favor `stack-md` for internal padding to maintain the "airy" feel required by the Glassmorphism style.

## Elevation & Depth

Depth is not communicated through traditional drop shadows, but through **Tonal Stacked Glass**.

1.  **Level 0 (Base)**: Deep Obsidian (#0f172a). No blur.
2.  **Level 1 (Cards/Navigation)**: Semi-transparent glass with 12px backdrop-blur. 1px stroke (white at 10% opacity).
3.  **Level 2 (Modals/Popovers)**: Increased opacity glass with 24px backdrop-blur. Apply a very soft, diffused outer glow using the primary indigo color at 5% opacity to simulate light passing through the glass.

Avoid black shadows; instead, use "Color Shadows"—subtle, blurred glows that inherit the hue of the primary or secondary color to reinforce the neon-on-dark aesthetic.

## Shapes

The shape language is consistently **Rounded (2)**. 

Standard containers and data cards use a **1rem (16px)** corner radius. Large-scale layout sections or hero containers use **rounded-xl (24px)**. Interaction elements like buttons and navigation pills utilize a full "pill" radius to distinguish them from the structural containers they sit upon. This contrast between the squircle cards and pill-shaped controls creates a dynamic, modern feel.

## Components

- **Data Cards**: Replace standard tables. Each card features a glass surface, a `title-lg` header, and a `mono-data` value area. Sparkline charts should be embedded directly into the card background as a subtle gradient mask.
- **Navigation Pills**: Active states use the Indigo-to-Purple gradient with white text. Inactive states are transparent with a subtle border.
- **Buttons**: Primary buttons are solid gradients. Secondary buttons are "Ghost Glass"—transparent with a 1px white border and backdrop-blur.
- **Input Fields**: Darker than the card surface (10% opacity black overlay) with a bottom-only 2px border that glows Indigo on focus.
- **Sleek Lists**: Rich list items include a small circular avatar/icon container and a right-aligned "Trend Indicator" (Emerald for up, Rose for down).
- **Glass Modals**: Centered with a heavy 40px backdrop-blur on the "underlay" to completely isolate the user's focus.
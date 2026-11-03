---
name: halden-design-system
description: The Halden look, components, motion and copy voice. Use before designing or writing any page, section, block or copy for the Halden theme, or when judging whether something matches the client's sample.
---

# Halden design system

The original design reference (`reference/`, kept locally, not in git) is ported into the theme. The theme loads that CSS globally as `theme/halden/assets/css/site.css`, so **every class below works on every page**. Compose pages from these components first; write page CSS only for what they cannot do.

## Tokens (CSS custom properties on `:root`)

- Dark surfaces: `--bg` `#171210`, `--bg2`, `--bg3`, `--bg4`; lines `--line`, `--line2`. Text on dark: `--tx`, `--tx2`, `--tx3`.
- Light "paper" surfaces (`.paper` section): `--paper`, `--paper2`, `--paper3`; text `--ink`, `--ink2`, `--ink3`.
- Accent red (one per view, for the primary action and markers): `--red`, `--red-h` (hover), `--red-tx` (red text on dark), `--red-mark`.
- Lake blue (cooling, water, secondary data): `--lake`, `--lake2`, `--lake-deep`.
- Fonts: `--fd` Poppins (display headings), `--ft` Inter (body), `--fm` JetBrains Mono (numbers, labels). Spacing/layout: `--gut`, `--max`, `--sec` (section padding). Radius `--r-sm`. Easing `--ease`, `--ease-out`.

Use tokens, never raw hex. Look up the full list in `reference/sample.css` line 3.

## Components (class → use)

| Class | Use |
|---|---|
| `.sec` / `.sec.paper` | Section with standard vertical rhythm; `.paper` flips to the light surface. Alternate dark and paper to pace a page. |
| `.wrap` | Centered content column with gutters. Every section's content sits in one. |
| `.head`, `.head--split` | Section header: `h2` (big, tight) + intro `p`; split puts them side by side on desktop. Optional `p.cap` eyebrow above. |
| `.btn` + `--red` / `--ghost` / `--ink` / `--line` / `--ai` / `--sm` | Buttons. `--red` = primary (one per section), `--ghost` on dark, `--ink`/`--line` on paper. Arrow icon SVG inside primary buttons. |
| `.chips` | Small spec pills under a statement. |
| `.story`, `.step` | Sticky media + scrolling steps (Power/Cooling/Security/Connectivity). |
| `.netband`, `.carriers` | Carrier logos/names band. |
| `.loc`, `.loc__map`, `.lpin`, `.lc` | Aerial map with pins and location cards. |
| `.ai`, `.ai-badge`, `.ai-grad`, `.specs2`, `.srow` | AI/GPU dark section with gradient numerals and spec rows. |
| `.also`, `.svc`, `.svc--type` | Service cards with image, body, price line. |
| `.proof`, `.bigq`, `.qrail`, `.qc` | Testimonials: big quote + scrolling rail. |
| `.journey` | 4-step timeline ("first call to live rack"). |
| `.finale`, `.finale--type`, `.pm` | Closing CTA band with price-match note. |
| `.plans`, `.plan`, `.stepper` | Interactive plan picker (a block: `halden/plans`). |
| `.tag`, `.cap`, `.mono`, `.icon` | Eyebrow label, caption, numeric text, 24px stroke icon. |

Components that are interactive are blocks (see `wp-block-theme` skill); the rest are plain markup with these classes.

## Section blocks (ready-made, reuse with your own content)

Every one renders the sample's section when used with no attributes; pass arrays/strings in the block JSON to give it this page's content. Read each `theme/halden/blocks/<name>/block.json` for the exact attributes.

| Block | Section |
|---|---|
| `halden/page-hero` | Inner-page opening (h1, lede, two CTAs, optional image + facts row) |
| `halden/hero-video` | Home only: scroll-scrubbed video hero |
| `halden/plans` | Plan picker with 3D rack; honours `?plan=<id>` |
| `halden/story` | Sticky media + 4 steps with live line graphics, carriers band |
| `halden/location` | Aerial map with pins and location cards |
| `halden/ai-gpu` | Dark AI/GPU feature with spec rows |
| `halden/service-cards` | Linked service cards |
| `halden/quotes` | Big testimonial + quote rail |
| `halden/journey` | 4-step timeline |
| `halden/finale` | Closing CTA band (every page ends with one) |

## Layout and rhythm

- One idea per section; a section = `.head` + one component + optional CTA.
- Headings: sentence case, short (aim ≤ 8 words), `h2` per section, one `h1` per page.
- A new page opens with a page hero: dark, `h1` + one-sentence lede + one primary and one secondary button. Use the `halden/page-hero` block.
- Every page ends in a `.finale` CTA that leads to `/contact/` (quote) or `/contact/#tour`.
- Mobile first: the CSS breakpoints are 600, 900, 1000, 1080px. Check 375px width; no horizontal scroll.

## Motion

Motion explains something (power flowing, water cooling, packets moving, a rack filling); it is never decoration for its own sake.

- Entrance: `site.js` fades an element up once when it scrolls into view. In block `render.php` markup add `data-reveal` (stagger with `style="--d:1"`…`3`); on core blocks add className `reveal` (stagger with `reveal-d1`…`reveal-d3`).
- Canvas animations draw only while on screen (IntersectionObserver) and stop when off screen.
- `prefers-reduced-motion: reduce` → show the final frame, no loops, no scroll-scrubbing.
- Animate `transform` and `opacity` only in CSS.

## Copy voice

Plain, specific, confident. Canadian English (centre, colour). Numbers over adjectives: "25 kW per rack", "quoted in one business day". Second person ("your rack"). Short sentences. Prices in CAD with "/ month".

## Brand facts (Halden Racks, a fictional company)

Halden Racks is invented for this portfolio prototype: no real company, place, person or contact detail may appear. Use only the facts below and keep them consistent everywhere. Since everything is fictional, nothing gets a `verify` mark.

- **Name:** Halden Racks (short form "Halden"); legal name Halden Racks Inc.; founded 2014; about 40 staff.
- **Place:** the lakeside town of Port Alder. Data centre at **200 Foundry Row**; head office at **18 Quay Street, Suite 400**, a two-minute walk away. Landmark: **Central Station**, on the rail line beside the data centre. **Alder Lake** lies to the south.
- **Halls:** **HAL1** (general colocation, up to 8 kW per rack) and **HAL2** (high density, up to 25 kW per rack, 208 V three-phase).
- **Power:** N+1 UPS, diesel generators, 48 h of fuel on site at full load.
- **Cooling:** deep lake water cooling, cold water drawn from Alder Lake through the town's lake-water loop.
- **Security and compliance:** SOC 2 Type II, PIPEDA and PHIPA, biometric access plus access card, 24/7 on-site staff, unescorted access for your team, data stays in Canada.
- **Network:** 400+ networks in the building; **AlderIX** internet exchange at 100 Gbps; carriers **Northline Fibre, Meridian Transit, Arcwave Networks, Polar Edge**; cross connects in 1–2 business days, no cap.
- **Prices (CAD / month):** per U from $149; quarter rack $749; half rack quoted; full rack from $1,999; private suites quoted; dedicated servers from $49 (rented hardware, month to month; don't name a hardware vendor); Cloud Connect from $500 (private links to AWS, Azure, Google Cloud and Cloudflare, 100 Mbps–10 Gbps); remote hands from $100 / hour. Price match: we beat a comparable quote by 10%.
- **Contact:** phone **+1 800 555 0142** (one number for sales, tours and support); sales@haldenracks.example, support@haldenracks.example, careers@haldenracks.example. Sales and tours Monday to Friday, 9 am to 5 pm; support 24/7.
- **Customers (fictional people; use these exact quotes):**
  - Big quote: "Halden gives us colocation that is reliable, scalable and cheaper than the cloud." — **Priya Natarajan**, CTO, Tidewater Games. Result: 10 to 15% lower cloud bill.
  - "Consistent uptime, a clean and modern floor, and support that actually answers." — **Marcus Bell**, President and CEO, Ridgeline Logistics. Result: Better latency post-move.
  - "Strong partnerships run on trust and responsiveness. That's why we build on Halden." — **Kestrel IT**, MSP partner. Result: Rack live in 24 hours.
  - "We could not have built our business without Halden." — **Owen Fraser**, Owner, Brightline Media. Result: Client since 2016.
  - "Our costs and worries have gone down every year. Their responsiveness is remarkable." — **Dr. Elena Sorensen**, Founder, Juniper Clinics. Result: 100% Canadian residency.
- **Images** (all in `assets/images/`, 1280×720, stock from Mixkit's free licence). Alt text must describe what is actually in the picture:
  - `hero-poster.webp`: a dark contained server aisle lit in blue, glass doors at the far end.
  - `power.webp`: close-up of a circuit board with glowing amber components.
  - `cooling.webp`: a cold aisle between glass-fronted cabinets, lit blue.
  - `security.webp`: server racks beside a mounted screen showing a login panel.
  - `meet-me-room.webp`: patch panels with neatly bundled grey network cables.
  - `high-density.webp`: a technician working in a blue-lit aisle of server racks.
  - `servers.webp`: close-up of server fronts with blue and green status lights.
  - `map.svg`: stylised map of Port Alder; pins at data centre 37/38, head office 73/27, Central Station 40/80.
  - Hero video: `hero-aisle.mp4` (in `assets/video/`), a slow move down the same blue-lit aisle.

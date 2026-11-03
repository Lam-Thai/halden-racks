# Halden Racks — custom WordPress block theme

A premium, motion-rich marketing site for **Halden Racks**, a fictional colocation company, built as a fully editable WordPress block theme. It shows that high-end design and animation don't have to give up WordPress's editor.

- **Live site:** https://lam-thai.github.io/amanah-prototype/
- **Try the WordPress admin** (opens in your browser, nothing to install): https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/Lam-Thai/amanah-prototype/main/blueprint.public.json

## What I built

- **Custom block theme, no page builder:** 20 custom blocks (scroll-scrubbed video hero, a Three.js 3D rack plan picker, a sticky story scroller with live canvas graphics, an interactive map, a quote and tour form with validation). Every one edits in the WordPress editor with a live preview, and has no build step.
- **9 pages and a blog**, with conversion flows: the plan picker carries the chosen plan and quantity into a pre-filled quote form.
- **Content as code:** pages live as block-markup files and are imported idempotently, so every fresh WordPress (local or the browser demo) is identical.
- **Free hosting:** a crawler exports WordPress to static files on GitHub Pages; a WordPress Playground blueprint boots a real, editable copy in the visitor's browser.
- **Performance and accessibility:** scripts load only on pages that use them; animations pause off-screen and respect reduced motion; keyboard-friendly tabs, forms and menu.
- **AI-assisted workflow:** project agents and skills in `.claude/` (page builder, motion engineer, theme engineer, QA reviewer) built the pages in parallel against shared conventions.

Halden Racks, its people, places and contact details are fictional. Video and photos are Mixkit stock (free licence); fonts are open-source (Poppins, Inter, JetBrains Mono).

Everything here is free: no hosting bill, no paid plugins, no paid themes.

---

## The 30-second picture

```
 your PC                                   the internet (free)
 ──────────                                ───────────────────
 theme/halden/   ──►  WordPress running ──► 1. Website link   (GitHub Pages)
 (design + code)      on your PC            2. "Try the admin" link (WordPress Playground)
 seed/ (content)      (Playground)
```

- **WordPress Playground** runs a full WordPress *inside Node.js* on your computer. No PHP, MySQL or XAMPP to install.
- The **theme** (`theme/halden/`) is the design: colours, fonts, header, footer, and the animated sections.
- The **content** (`theme/halden/seed/`) is the text of each page, stored as files so every fresh WordPress looks the same.
- To share it, we **export** WordPress to plain files and host them on **GitHub Pages** (free). A second link opens a real WordPress admin in the visitor's browser via **playground.wordpress.net** (free).

## Words you'll meet

| Word | Meaning |
|---|---|
| Theme | The design of a WordPress site. Ours is a *block theme*: every page is built from blocks. |
| Block | One piece of a page (a heading, a button, the 3D plan picker). You add, move and edit blocks in the editor. |
| Page / Post | Pages are fixed (About, Contact). Posts are dated articles (News). |
| wp-admin | WordPress's back office, at `/wp-admin/`. |
| Menu | The header links. Edit in wp-admin → Appearance → Menus. |

## Run it on your computer

You need [Node.js](https://nodejs.org) (already installed on this PC). In a terminal in this folder:

```bash
npm install
```

```bash
npm run dev
```

Wait for `Ready! WordPress is running on http://127.0.0.1:9400`, then open:

- the site: http://127.0.0.1:9400
- the admin: http://127.0.0.1:9400/wp-admin/ (you're logged in automatically)

Stop it with `Ctrl + C`. Each start is a fresh WordPress; the content is re-imported from `theme/halden/seed/`.

## Change things

- **Text on a page:** edit `theme/halden/seed/pages/<page>.html`, save, reload the browser. Or edit in wp-admin → Pages (handy for trying things; the files win on the next restart).
- **Colours / spacing:** `theme/halden/assets/css/site.css` (global) or `assets/css/pages/<page>.css`.

## Publish (free)

See `docs/HOSTING.md` for the step-by-step guide.

# Lumina Dental Care: WordPress local-service site

A dental-clinic website built as a custom WordPress theme + companion plugin. The design
(tokens, radii, topbar, slide-in nav, Display panel, light/dark) is carried over from the portfolio.

```
wp-content/themes/lumina-dental/         presentation: templates, CSS, JS (no build step)
wp-content/plugins/lumina-clinic-core/   content types, clinic settings, schema, booking, starter content
```

## Run it in XAMPP

1. Start Apache and MySQL in the XAMPP control panel.
2. In phpMyAdmin create a database named `lumina_dental` (collation `utf8mb4_general_ci`).
   `wp-config.php` already points at XAMPP defaults (user `root`, empty password).
3. Open `http://localhost/untitled%20folder/` and finish the WordPress install screen.
   (Tip: rename the folder to `lumina-dental` first to avoid the space in the URL.)
4. Appearance → Themes → activate **Lumina Dental**.
5. Plugins → activate **Lumina Clinic Core**. This creates the starter content, the menu,
   sets the Dhaka time zone and `/%postname%/` permalinks.
6. Fill in **Clinic → Clinic details** with the real name, address, phone, hours, coordinates.

## What is where

| Need | Where |
| --- | --- |
| Name, address, phone, hours, map pin | Admin → Clinic |
| Treatments (one page each, with FAQs and steps) | Admin → Services |
| Neighbourhood landing pages | Admin → Areas served |
| Reviews | Admin → Reviews |
| Booking requests | Admin → Appointments (also emailed) |
| Menu | Appearance → Menus (location: Primary) |

## Local SEO built in

- City-qualified titles and meta descriptions (`/services/teeth-whitening/` → "Teeth whitening in Dhaka | …").
- One H1 per page, breadcrumbs matching `BreadcrumbList` markup, canonical URLs.
- JSON-LD: `Dentist`/`LocalBusiness` (NAP, geo, hours, areas), `Service`, `FAQPage`, `BreadcrumbList`.
- Service pages and per-neighbourhood pages, all in WordPress's built-in `/wp-sitemap.xml`.
- NAP is stored once and reused everywhere, so it cannot drift between pages.
- The theme stands down its own title/meta tags if Yoast, Rank Math or AIOSEO is active.

## Before you launch

- **Replace the sample reviews.** They are labelled "Sample" on the page and are deliberately
  *excluded* from star-rating markup. Fabricated ratings break Google's policies and consumer law.
  Untick "Sample review" once a review is real. The rating badge and `aggregateRating` then appear.
- Replace placeholder copy, prices (৳), dentist details and the trust-strip claims on the home page
  with statements that are true for the clinic.
- Email: XAMPP does not send mail by default. Requests are always saved under Appointments;
  install an SMTP plugin (e.g. WP Mail SMTP) so notifications also reach the inbox.
- Add a privacy policy page; booking collects name, phone and optional email.
- Google Maps only loads after a visitor taps "Show map".

## Booking form

Posts to `admin-post.php?action=lumina_book`. Works with or without JavaScript. Protected by a
hidden honeypot, a signed time token, a 5-per-hour limit per IP and server-side validation
(closed days from your opening hours, 90-day window, service must exist).

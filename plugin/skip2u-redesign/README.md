# Skip 2 U Tas Redesign

Front-end redesign delivered as a plugin, so it can be switched off.

## Why a plugin

Nothing in `siteorigin-corp-child` is edited. The redesign renders through the
`template_include` filter, supplying its own header, footer, homepage and
shared pre-footer block. **Deactivating the plugin returns the site to exactly
what it was**, with no files to restore.

## Install

1. Confirm **Advanced Custom Fields Pro** is active. The plugin shows an admin
   error and stays dormant without it.
2. Upload `skip2u-redesign.zip` via *Plugins > Add New > Upload Plugin*, then
   activate.
3. Seed the content, either:
   - `wp s2u seed` (add `--dry-run` first to preview, `--force` to re-run), or
   - *Site Content > Seed content* (`/wp-admin/admin.php?page=s2u-seed`), press **Run seeder**.
4. Assign the two footer menus under *Appearance > Menus*:
   - Redesign: Footer quick links
   - Redesign: Footer services
5. Check the homepage has a featured image. The hero uses it, falling back from
   the ACF *Hero > Background image* field.

## Revert

Deactivate the plugin. That is the whole procedure.

Field values stay in the database, so reactivating picks up where it left off
and the seeder will not re-run. To compare a single page against the old theme
without deactivating, append `?s2u=off` to its URL.

## What it deliberately does not touch

The WooCommerce **cart, checkout, my-account and order-received** screens keep
the original theme. That is the booking and payment path, and restyling it
belongs in its own change with its own testing. See `s2u_should_render()` in
`inc/render.php`; the `s2u_should_render` filter can widen this later.

Posts and archives also stay on the theme, since no design exists for them yet.

## Where the content lives

| Content | Edited at |
| --- | --- |
| Header details, top bar, logo | Site Content > Header |
| Guide, questions, closing block | Site Content > Shared: Guide / Questions |
| Contact details, map, hours | Site Content > Shared: Contact |
| Footer text, socials, copyright | Site Content > Footer |
| Every homepage section | Edit the front page > Homepage sections |

The booking form is untouched: templates call `do_shortcode('[bin_form]')`, so
`inshirt_form()` and its AJAX handlers run exactly as before.

## CSS notes

`assets/css/s2u.css` is generated from the standalone design by
`build-css.py`; edit the design and re-run `build.sh` rather than editing the
output. Two constraints are baked in:

- **Scoped under `.s2u`.** The redesign and the old stylesheet coexist, so
  neither may reach the other's markup. Legacy page content renders outside any
  `.s2u` wrapper and is untouched.
- **px, not rem.** The child theme sets a fluid
  `html{font-size:calc(10px + …)}`, which resolves to roughly **12px** at
  1440px wide and 11.2px below 1190px. Any `rem` here would be silently
  rescaled against that, so the build converts them all.

Headings use **Axiforma**, already self-hosted by the child theme. Body copy
stays on Inter.

## Rebuilding the zip

```bash
./build.sh
```

Regenerates the stylesheet from `index.html` at the repository root, appends
`assets/css/extra.css`, and writes `skip2u-redesign.zip`.

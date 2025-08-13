# Memory Vault

A private, family-first platform to capture, curate, and relive memories (photos, videos, audio, notes) with **granular sharing**, **powerful curation**, and **delightful storytelling**. Built with **Laravel 12.x + Blade/HTMX**, queues, and a search layer, optimised for deployment at the root of **melknep.uk** on a DigitalOcean droplet with CI/CD from the outset.

---

## Vision
- **Private by default.** Your content, your rules. No accidental public links; only expiring shares.
- **Curated, not dumped.** Memories organised into **Timelines**, **Stories**, **Albums**, **People**, and **Places**.
- **First-class family UX.** Mobile-first capture, instant thumbnails, quick reactions, and rich playback.
- **Explainable permissions.** Per-item and per-circle (e.g., Family, Friends) controls, always visible "who can see this?".
- **Durable & exportable.** One-click exports (ZIP + JSON) and yearbooks.
- **Offline-friendly mindset.** PWA install, resilient uploads, background retries.
- **Hackable.** Clear extension points for automations and new features.

---

## Product Principles
1. **Minimise friction.** Two–three taps to capture, tag, and share.
2. **Granular ACL without confusion.** Simple presets with advanced overrides.
3. **Curation as a superpower.** Auto-tags from EXIF/faces/places; manual tags; Stories to transform dumps into narratives.
4. **Delight in playback.** Smooth browsing, crisp media, and rich context.
5. **Deploy light, run lean.** Optimised for a single droplet but scalable.
6. **Observability and resilience.** Transparent processing states and CI/CD pipelines for safe iteration.

---

## Deployment Vision (Root Domain)
- **Domain:** Serve the Vault app directly at `https://melknep.uk`.
- **Droplet setup:** Nginx + PHP-FPM 8.3, Redis, Postgres, Meilisearch, FFmpeg, ImageMagick.
- **Process management:** Supervisor for queues; Horizon dashboard (auth-protected).
- **Storage:** S3-compatible (DO Spaces or Backblaze B2) for originals and derivatives.
- **Backups:** Automated Postgres + storage backups to external bucket.
- **TLS & CDN:** Let’s Encrypt certificates; optional Cloudflare CDN.

---

## CI/CD Plan
- GitHub Actions workflow:
  - **Lint & static analysis:** Pint, Larastan.
  - **Test:** Pest (unit & feature).
  - **Build:** Vite/Tailwind.
  - **Deploy:** SSH/rsync to droplet, release into `/var/www/memory-vault/releases/<ts>`.
  - Run migrations, cache configs/routes/views, swap symlink, reload services.
  - Optional zero-downtime with blue/green approach.
- Secrets in GitHub Actions Secrets (`DROPLET_HOST`, `DROPLET_USER`, `SSH_KEY`, `ENV_FILE_BASE64`, S3/B2 creds).

---

## Roadmap Adjustments for Root-Domain Droplet & CI/CD

### Week 1 – Foundation & CI/CD
- Provision droplet; install stack; secure SSH.
- Configure domain `melknep.uk` → droplet.
- Breeze auth, Tailwind layout.
- GitHub Actions pipeline (lint, static analysis, test, build, deploy to staging branch target).

### Week 2 – Media Pipeline
- Install/configure FFmpeg + ImageMagick.
- Implement `ProcessMedia` job (thumbnails, web derivatives, EXIF ingest, pHash).
- Wire to queues under Supervisor; verify Horizon on staging.

### Week 3 – Permissions & Secure Delivery
- Circles + item-level grants.
- Signed URLs for media delivery.
- Test expiry and ACL enforcement under real droplet load.
- Deploy to production branch target.

### Post-MVP
- Face clustering microservice.
- Map view & GPS clustering.
- Advanced search facets.
- PWA offline caching + queued background uploads.
- Blue/Green deploy option for minimal downtime.

---

## Testing Strategy
- Local/staging parity tests.
- CI pipeline runs: Pint, Larastan, Pest, browser snapshots for HTMX.
- Load test key endpoints before production switch.

## Quickstart

### Seed an initial admin user

```
php artisan db:seed --class=AdminUserSeeder
```

Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in your `.env` before seeding.

### Generate an invitation

After logging in as an admin, visit `/admin/invitations` to create an invitation link. Share the generated link with invitees so they can register.
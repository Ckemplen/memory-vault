# agents.md (Codex Agent Guide)

- **Root-domain awareness:** All routes/pages served from `/` at `melknep.uk`; no subdomain assumptions.
- **Optimise for droplet constraints:** Minimise memory/CPU spikes; batch heavy jobs.
- **Deployment safety:** Migrations reversible; cache clears in deploy script; healthcheck endpoint.
- **CI/CD integration:** All features must pass lint, static analysis, tests before merge.
- **Media processing:** Always queued; scale down for droplet hardware.
- **Security:** Signed URLs only; fail closed if storage access fails.
- **Observability:** Log each processing stage; Horizon/Telescope in staging only; Sentry in production.

## Development Rules
1. Use constructor DI, strict typing, and return types.
2. Controllers thin; Services/Actions handle business logic.
3. Enforce ACL in policies + Blade guards.
4. HTMX endpoints return minimal fragments.
5. No public bucket access; all media via signed route.

## Testing Requirements
- Upload → process → ready state covered.
- ACL enforcement verified.
- HTMX fragment returns validated.
- Performance tested on droplet hardware.
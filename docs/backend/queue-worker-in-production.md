# Keeping the Queue Worker Alive in Production

`php artisan queue:work` is a **foreground process tied to the terminal**.
If the terminal closes, the SSH session drops, or the server reboots — the worker dies and queued jobs stop running.
Production needs a way to keep it (and restart it) automatically.

## Why a process supervisor is needed
1. `queue:work` is a normal PHP process — no auto-restart on its own.
2. Jobs may grow the worker's memory; workers must be killed and restarted periodically.
3. After a crash or deploy, the worker must come back without manual SSH.
4. You usually want **multiple workers** in parallel for throughput.

## Option 1 — Docker `worker` service (fits this project)
Add a second service in `docker-compose.yml` using the same Laravel image:

- Command: `php artisan queue:work --tries=3 --timeout=120`
- `restart: unless-stopped` → Docker auto-restarts on crash / reboot.
- Scale with `docker compose up --scale worker=4`.

Pros: zero extra tooling, already inside your stack.
Cons: no UI to monitor failed jobs / throughput.

## Option 2 — Supervisor (classic Linux)
A small process manager. You write a `.conf` file:

```
[program:laravel-worker]
command=php artisan queue:work --tries=3 --timeout=120
autostart=true
autorestart=true
numprocs=4
```

Pros: battle-tested, runs anywhere.
Cons: extra install + config; not ideal inside Docker (Docker is already a supervisor).

## Option 3 — systemd unit
Native Linux service manager. Create `/etc/systemd/system/laravel-worker.service`, then `systemctl enable --now laravel-worker`.

Pros: built into every modern Linux server.
Cons: VPS / bare-metal only — awkward in container setups.

## Option 4 — Laravel Horizon (recommended for Redis)
Official Laravel package. Replaces `queue:work` with `php artisan horizon` **and** gives a dashboard at `/horizon`:

- Live throughput, runtime per job, memory, failed jobs.
- Retry / delete failed jobs from the UI.
- Auto-scaling worker counts.

Still needs **one of the above** (Docker / Supervisor / systemd) to keep the `horizon` process itself alive — Horizon doesn't replace the supervisor, it sits on top of `queue:work`.

Pros: full visibility, made for Redis (which this project uses).
Cons: small extra install; another dashboard route to secure.

## Suggested adoption path for this project
1. **Phase 1 (learning):** run `php artisan queue:work` manually inside the app container — see jobs fire.
2. **Phase 2 (production):** add a `worker` service to `docker-compose.yml` with `restart: unless-stopped`.
3. **Phase 3 (polish):** install Horizon and swap the worker command to `php artisan horizon`.

## Interview talking points
1. Workers are normal PHP processes — they die on crash or terminal close.
2. Process supervisors (Docker / Supervisor / systemd) restart them automatically.
3. Horizon is a **monitoring + auto-scaling layer**, not a replacement for the supervisor.
4. Workers must restart periodically — long-running PHP leaks memory; `--max-jobs` and `--max-time` flags help.
5. After every deploy, run `php artisan queue:restart` so workers reload the new code.

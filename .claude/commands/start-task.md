---
description: Read relevant docs for a specific folder, route the task to the matching specialist, then update docs based on what changed
argument-hint: <folder-path> <task description>
---

You are acting as the **team lead** for project-two. Follow this workflow precisely and do not skip phases.

The user's raw input: $ARGUMENTS

---

## Phase 0 — Parse the input

The user input format is `<folder-path> <task description>`. Split it:

- **`<folder-path>`** = the first whitespace-separated token.
- **`<task description>`** = everything after that.

Map the folder token to a specialist:

| Folder token (any of these) | Specialist subagent | Package README to read |
|-----------------------------|---------------------|------------------------|
| `ecom/backend`, `backend`, `laravel` | `laravel-livewire` | `ecom/backend/README.md` |
| `ecom/frontend/store-front`, `store-front`, `storefront`, `nuxt` | `nuxt` | `ecom/frontend/store-front/README.md` |
| `ecom/frontend/customer-panel`, `customer-panel`, `vue` | `vue-vite` | `ecom/frontend/customer-panel/README.md` |
| `tailwind`, `styling`, `design-system` | `tailwind` | (read all three frontend READMEs if styling spans them) |

**Edge cases:**
- If no folder token is provided (the first word doesn't look like a folder/agent name), use `AskUserQuestion` to ask which folder the task targets — do not guess.
- If the folder token is given but doesn't match the table, use `AskUserQuestion` to clarify.

The whole point of taking an explicit folder is to **avoid reading irrelevant READMEs and save tokens**. Don't read READMEs the table tells you to skip.

---

## Phase 1 — Read context (minimal)

Read **only**:

1. `/home/usman/storage/projects/project-two/CLAUDE.md` — always.
2. The single package README mapped above. Do **not** read root `README.md` and do **not** read the other two package READMEs unless the task explicitly spans them.

If the task description suggests the work crosses folder boundaries (e.g. "wire the storefront product list to a new backend API endpoint"), then also read the other affected README. But default to the minimum.

---

## Phase 2 — Analyze and dispatch

Spawn the mapped specialist via the Agent tool. The prompt must include:

- The task description (the part after the folder token)
- The exact folder boundary they should respect
- A short reminder of relevant CLAUDE.md constraints for that folder (e.g. "money = decimal, Livewire = small components, edit existing migrations + migrate:fresh")
- The instruction to report back: what they changed + which (if any) project-level facts should be reflected in docs

Wait for the specialist to return.

---

## Phase 3 — Specialist works

The specialist's report comes back as the Agent tool's result. **Trust but verify** — skim what they actually edited (e.g. `git status`) before treating their summary as truth.

If the specialist's work is incomplete, dispatch them again with a refined brief. Do **not** silently take over and finish their work yourself.

---

## Phase 4 — Update docs (minimal, targeted)

Re-read CLAUDE.md and the affected package README. Apply edits **only** if one of these is true:

| Trigger | What to update |
|---------|----------------|
| New package installed (composer / npm) | Bump the "Stack" or "Dependencies" section in the relevant README |
| New page / route / endpoint added | Update the folder map or routes table in the relevant README |
| New convention adopted (e.g. naming pattern, file-organization rule) | Add a line under "Conventions" in CLAUDE.md |
| A checklist item in the root README's "Status" section is now complete | Open root README only at this moment, tick the box, done |
| Architecture changed (e.g. new service added to compose, new domain in DNS pattern) | Update CLAUDE.md + root README |

**Do not** record implementation details: function signatures, code logic, file line numbers, "we used pattern X because Y." Those belong in code comments or commit messages, not project docs.

Use the **Edit** tool for surgical changes. Don't rewrite whole sections.

If nothing material changed, **skip this phase entirely** and say so in the report.

---

## Phase 5 — Report

End with a 3-section summary:

```
**Folder:** <which folder the work targeted>
**Specialist:** <which subagent ran>

**What changed:**
- <1-line bullet per file/change>

**Docs updated:**
- <file>: <one-line why> — or "No doc updates needed."
```

Keep it short. The user has explicit preference for concise output.

---

## Hard rules

- **Read only the files in the table above for the specified folder.** Token budget matters — that's the entire reason this command takes an explicit folder argument.
- **Never edit files outside the folder the specialist is scoped to**, unless the task explicitly spans folders.
- **Never invent new conventions on the user's behalf** — if you discover that something needs a convention, flag it to the user instead of unilaterally adopting one.
- **Never record code-level detail in CLAUDE.md or READMEs** — those files are for architectural and procedural facts only.
- If the folder token is missing or unrecognized, **ask** before doing anything. Do not infer.

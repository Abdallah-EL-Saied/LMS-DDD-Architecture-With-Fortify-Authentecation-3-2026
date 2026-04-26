# Fatema Alzahraa Academy — LMS

> **Stack:** Laravel 12 · Livewire 4 · Flux UI · DDD · Pusher · Paymob · 2Checkout
> **Last updated:** April 5, 2026

---

## Table of Contents

1. [Overview](#overview)
2. [Roles & Permissions](#roles--permissions)
3. [Specializations & Subjects](#specializations--subjects)
4. [Enrollment System](#enrollment-system)
5. [Session System](#session-system)
6. [Attendance System](#attendance-system)
7. [Pricing & Payment](#pricing--payment)
8. [Exam System](#exam-system)
9. [Payroll System](#payroll-system)
10. [Complaints & Requests](#complaints--requests)
11. [Notifications](#notifications)
12. [DDD Project Structure](#ddd-project-structure)
13. [Key Technical Decisions](#key-technical-decisions)

---

## Overview

The academy provides **1-on-1 online sessions** (teacher + one student only) via any video platform. The teacher manually adds the meeting link — no Zoom API integration for now. No recorded programs or video content.

### Business Model
- Owner adds **programs and plans** with details and pricing
- **Dual pricing**: Egyptian Pound for Egyptian students + USD for international
- Teachers apply via a **public job application form**
- After acceptance, admin creates their account and sets contract terms
- Student subscribes → selects subjects and schedule → **each session is independently assigned to a teacher**
- No fixed teacher-student binding — every session is independent

---

## Roles & Permissions

> Multiple roles can be assigned to one person **except Student**, which cannot be combined with any other role.

| Role | Responsibilities | Exclusive Permissions |
|------|-----------------|----------------------|
| **Super Admin** | System owner | System settings, pricing, payment methods, revenue reports |
| **Admin** | Daily academy manager | Create teacher accounts, set contract terms, payroll |
| **Supervisor** | Admin assistant | Run job application form, accept teachers & send report to admin, assign/change teacher on session, approve schedule change requests, handle & escalate complaints |
| **Teacher** | Assigned to sessions | Add meeting link, record student attendance manually, build and assign exams |
| **Student** | Subscribed learner | Subscribe, choose subjects & schedule, attend sessions, submit complaints/requests |

### Key Rules
- **Student only** — cannot be combined with any other role
- **Supervisor** does NOT create teacher accounts — sends an acceptance report to admin only
- **Admin** alone creates teacher accounts and sets contract terms

---

## Specializations & Subjects

### Specialization
- Organizational info added by admin (Islamic Studies, Mathematics, Arabic Language...)
- A teacher can have **multiple specializations**
- NOT used to assign a teacher to a session — for classification only

### Subject
- Admin adds it with a full description so the student knows what they will study
- Linked to a specialization (e.g. "Islamic Studies" → Quran, Hadith, Fiqh, Tajweed)
- Student selects **one subject per day** in their schedule

```
subjects: id, name, description, specialization_id, icon, color, is_active, timestamps
```

---

## Enrollment System

### 4-Step Enrollment Flow
1. Choose plan (monthly / annual) — price shown automatically based on geo-location
2. Choose a subject for each day (Saturday = Quran, Sunday = Hadith...)
3. Choose days and time slots within academy schedule configured by admin
4. Pay → sessions are created → admin/supervisor notified to assign a teacher to each session

### Multi-Enrollment Rules
```
✅ Student can hold multiple active subscriptions simultaneously
✅ Upgrade monthly → annual: new subscription added, old one runs until expiry
✅ Add another monthly plan: added alongside, not replacing
❌ Time slots must not conflict across different active subscriptions
```

### Schedule Change Rules

| Subscription Type | Change Mechanism | Who Approves |
|------------------|-----------------|--------------|
| **Monthly** | Student submits request: new slots + reason | Admin or Supervisor |
| **Annual** | System opens renewal window automatically each month | No approval needed |
| **Annual (no update)** | Window closes → previous month schedule reapplied | Automatic |

### Annual Schedule Renewal Window
```
Opens: 24 hours before the last session of the current month
Closes: 12 hours before the first session of the new month
If student doesn't update → previous schedule applied automatically
```

### Monthly Schedule Change Approval Flow
```
Student submits request (new slots + reason)
    ↓
Admin / Supervisor reviews
    ↓
Approved → upcoming sessions updated immediately + student notified
Rejected → student notified with reason + old schedule kept
```

---

## Session System

### Session Structure
```
sessions:
  id
  enrollment_slot_id       FK — booked slot from subscription
  teacher_id               FK — teacher assigned to THIS specific session
  original_teacher_id      FK — original teacher (kept for history even after substitution)
  subject_id               FK — subject chosen by student for this day
  scheduled_at             timestamp (always UTC)
  duration_minutes         from Plan (not fixed)
  meeting_link             added manually by teacher
  link_added_at
  status                   scheduled | live | completed | cancelled | substituted
  substitution_reason
  timestamps
```

### Assigning a Teacher to a Session
- Admin or Supervisor assigns a teacher to each session individually
- **Automatic validation:** teacher's total hours on that day ≤ contract max_daily_hours
- If limit exceeded → assignment rejected with a clear message
- Same teacher can be assigned to multiple sessions on same day (within the limit)

### Meeting Link & Join Button Logic
```
Teacher adds any link (Zoom / Meet / any platform) manually to the session
    ↓
Exactly 5 minutes before scheduled_at:
  → Join button appears for student and teacher (via Pusher — no refresh needed)
    ↓
15 minutes after session end (scheduled_at + duration_minutes + 15 min):
  → Button disappears automatically
    ↓
If teacher hasn't added a link:
  → "Link not added yet" message shown

Pressing the button = auto-records join_time (auto attendance)
```

### Substitute Teacher
```
If teacher is absent or unavailable for any reason:
  Admin / Supervisor assigns a substitute for THIS session only
  original_teacher_id preserved → record shows "delivered by substitute"
  Student's other sessions remain with their original assignments
  No permanent teacher change — every session is always independent
```

---

## Attendance System

### Two Independent Attendance Types

| Type | How Recorded | Who Records |
|------|-------------|-------------|
| **Auto** | Student or teacher presses join button | System automatically |
| **Manual** | After session ends | Teacher records for student |

### Scenarios in Reports
```
Attended and engaged     → Auto: present  | Manual: present  → ✅ Present
Opened link but absent   → Auto: present  | Manual: absent   → ⚠️ Conflict (both shown)
Didn't attend at all     → Auto: absent   | Manual: absent   → ❌ Absent
```

### Teacher Attendance
- Recorded from "Start Session" button → auto join_time
- Supervisor reviews and can manually mark teacher as absent
- Absence affects payroll calculation (deduction system)

---

## Pricing & Payment

### Geo-Pricing Logic
```
Egyptian price conditions (BOTH must be true):
  1. IP from Egypt → stored in Cookie (1 year)
  2. Primary payment method type is Egyptian (Paymob)

Either condition fails → international price in USD
```

### Scenario Table
| Scenario | IP | Primary Payment | Price |
|---------|-----|----------------|-------|
| Regular Egyptian | 🇪🇬 Egypt | Paymob | EGP |
| Tourist in Egypt | 🇪🇬 Egypt | Foreign Visa | USD |
| Egyptian abroad | 🌍 Outside | Paymob | USD |
| Regular international | 🌍 Outside | Foreign Visa | USD |
| VPN from Egypt | 🇪🇬 (VPN) | Foreign Visa | USD |

### Payment Method Rules
```
✅ Student can register multiple payment methods
✅ One is set as Primary → determines displayed price
✅ Changing Primary → affects next subscription only
✅ Active subscriptions not affected by Primary change
✅ Payment method saved for auto-renewal
```

### Payment Gateways
| Gateway | Audience | Fee | Methods |
|---------|---------|-----|---------|
| **Paymob** | Egyptian students | ~2.5-3% | Visa + Mastercard + Fawry + Vodafone Cash + Orange + Etisalat |
| **2Checkout** | International | 3.5% + $0.35 | Visa + PayPal + 45+ methods, 87 currencies |

---

## Exam System

### Exam Lifecycle
```
Teacher creates exam (general — not assigned to a student yet)
    ↓
Adds questions (MCQ / True-False / Short Answer / Essay)
    ↓
Assigns to student: sets opens_at + duration_minutes
    ↓
Before opens_at: countdown page showing time until start
    ↓
At opens_at exactly: exam opens automatically (Pusher — no refresh)
    ↓
During exam: countdown timer showing time remaining
    ↓
Time runs out: auto-saved and closed
    ↓
MCQ/T-F → instant auto-grade
Essay   → "Pending grading" → teacher grades manually
```

### Question Types
| Type | Grading |
|------|---------|
| MCQ (multiple choice) | Automatic |
| True / False | Automatic |
| Short Answer | Manual |
| Essay | Manual |

---

## Payroll System

### Contract Types
```php
enum ContractType: string {
    case Hourly     = 'hourly';      // rate × hours attended
    case PerSession = 'per_session'; // rate × sessions completed (any duration)
}
```

### Contract Fields (teacher_contracts)
```
type              — hourly | per_session
rate              — price per hour or per session
max_daily_hours   — daily hours cap (from contract)
available_days    — [0,1,2,3,4,5] working days
currency          — EGP | USD
starts_at         — contract start date
ends_at           — nullable
```

### Monthly Payroll Calculation
```
hourly:
  gross = Σ (duration_minutes / 60) × rate  for each completed session

per_session:
  gross = completed_sessions_count × rate

deductions:
  absence_deduction = absences_count × deduction_value (set by admin)

net = gross - total_deductions
```

### Payout Info
- Teacher registers bank IBAN or e-wallet number in their settings
- Data is **encrypted in DB** — admin sees last 4 characters only

---

## Complaints & Requests

### Logged-in User
- Via Messages page inside the platform
- Form: type (complaint / request) + subject + body
- Reply appears in-platform + Pusher notification

### Guest (not logged in)
- Via About / Contact page
- Form: name + email + subject + message
- Reply sent to the guest's provided email address

### Lifecycle
```
New → In Review → Escalated (optional) → Resolved → Closed
```

| Permission | Admin | Supervisor |
|-----------|-------|-----------|
| View all | ✅ | ✅ |
| Reply | ✅ | ✅ (own scope) |
| Escalate | ✅ | ✅ |
| Close | ✅ | ✅ |

---

## Notifications

### Pusher Channels
| Plan | Price | Concurrent | Decision |
|------|-------|-----------|---------|
| Sandbox | Free | 100 | ✅ Start here |
| Startup | $49/mo | 500 | When scaling |

### Real-Time Events
| Event | Receiver |
|-------|---------|
| Join button appears (5 min before) | Student + Teacher |
| Join button disappears (15 min after end) | Student |
| New schedule change request | Admin + Supervisor |
| Request approved / rejected | Student |
| Teacher assigned to session | Teacher |
| New complaint | Admin + Supervisor |
| Exam opens at scheduled time | Student |
| Dashboard stats update | Admin |

### DB Notifications (Scheduled)
- Session reminder 24 hours before
- Session reminder 1 hour before
- Low balance alert (last 2 sessions remaining)
- Subscription expiring in 7 days
- Annual schedule renewal window opened
- Exam result ready
- Schedule change approved / rejected

---

## DDD Project Structure

```
app/
├── Domains/
│   ├── User/
│   │   ├── Entities/User.php
│   │   ├── ValueObjects/TeacherContract.php
│   │   └── Repositories/IUserRepository.php
│   ├── Specialization/
│   │   ├── Entities/Specialization.php
│   │   ├── Entities/Subject.php
│   │   └── Repositories/ISubjectRepository.php
│   ├── JobApplication/
│   ├── Program/               # programs + subscription plans
│   ├── Enrollment/           # subscription + schedule + change requests
│   ├── Payment/              # Paymob + 2Checkout + invoices
│   ├── Session/              # 1-on-1 sessions + link + substitute
│   ├── Attendance/           # auto + manual attendance
│   ├── Complaint/            # complaints + requests + escalation
│   ├── Exam/                 # exams + questions + grading
│   ├── Payroll/              # payroll + deductions
│   └── Notification/         # Pusher + DB notifications
│
├── Application/
│   ├── Actions/
│   ├── DTOs/
│   └── Services/
│
├── Infrastructure/
│   ├── Models/
│   ├── Repositories/
│   ├── Listeners/
│   └── Services/
│       ├── GeoIpService.php
│       ├── PaymobService.php
│       ├── TwoCheckoutService.php
│       └── PusherService.php
│
└── Interfaces/
    ├── Http/Controllers/
    └── Livewire/
        ├── Admin/
        ├── Supervisor/
        ├── Teacher/
        └── Student/
```

---

## Key Technical Decisions

| Decision | Detail |
|---------|--------|
| **No Zoom/Meet API** | Teacher adds link manually — any platform |
| **Pusher for real-time** | Free Sandbox tier to start |
| **Paymob** | Egyptian students |
| **2Checkout** | International students |
| **No fixed teacher-student binding** | Each session assigned independently |
| **All timestamps UTC** | Displayed in user's local timezone |
| **Contract types** | `hourly` or `per_session` |

### Phase 0 Bugs (must fix first)

| File | Bug | Fix |
|------|-----|-----|
| `UpdateUserInput` DTO | Single `name` field | Split into `firstName + middleName + lastName` |
| `UpdateUserAction` | `changeName()` needs 3 params | Pass 3 separate params |
| `UpdateLastLoginListener` | Injects Concrete class | Inject Interface instead |
| `users:mark-inactive` command | Loads all records at once | Use `chunkById(500)` |
| `.env` | Sensitive data possibly committed | Clean up + verify `.gitignore` |

---

*Living document — updated at each development phase.*
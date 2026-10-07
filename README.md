✈️ TripPlanner

<p align="center">
  <strong>A real-time collaborative workspace for planning group trips.</strong><br>
  Plan itineraries, track shared expenses, manage packing lists, and stay connected in one place.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire 3">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=111827" alt="Alpine.js">
</p>

🌍 Overview

TripPlanner is a real-time collaborative travel planning application built with the Laravel TALL stack.

It brings the core parts of group travel into a single workspace: organize the itinerary, keep track of expenses, coordinate packing, communicate with the group, and export a complete trip report for offline use.

The application is designed around live collaboration, so changes made by one member can be reflected across connected clients without requiring manual page refreshes.

✨ Features

<table>
  <tr>
    <td width="50%">
      <h3>🗓️ Real-Time Itineraries</h3>
      <p>Create, edit, and organize trip activities while keeping the whole group synchronized through WebSockets.</p>
    </td>
    <td width="50%">
      <h3>💰 Expense Management</h3>
      <p>Record shared or personal expenses and maintain a unified financial summary of who paid what.</p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <h3>🎒 Collaborative Packing Lists</h3>
      <p>Build packing checklists and let members assign themselves to specific items.</p>
    </td>
    <td width="50%">
      <h3>💬 Rich Media Group Chat</h3>
      <p>Chat with text, images, PDF/document attachments, and browser-recorded voice notes.</p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <h3>🎙️ Voice Notes</h3>
      <p>Record and upload <code>.webm</code> voice messages directly from the browser using the native Web Audio API.</p>
    </td>
    <td width="50%">
      <h3>📄 PDF Trip Reports</h3>
      <p>Generate professional multi-page reports covering the itinerary, finances, and packing list.</p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <h3>👤 User Profiles</h3>
      <p>Manage custom avatars and travel preferences with dynamic UI updates.</p>
    </td>
    <td width="50%">
      <h3>⚡ Live UI Updates</h3>
      <p>Use Livewire, Alpine.js, and real-time broadcasting to create a responsive collaborative experience.</p>
    </td>
  </tr>
</table>

🧱 Tech Stack

Layer

Technology

Backend

Laravel 11 / PHP

Frontend

Livewire 3, Alpine.js, Tailwind CSS

Database

MySQL

Real-Time

Laravel Reverb / Pusher WebSockets

PDF Generation

barryvdh/laravel-dompdf

Architecture

Laravel + Livewire TALL Stack

🔄 Application Flow

        ┌──────────────────────┐
        │      TripPlanner     │
        └──────────┬───────────┘
                   │
       ┌───────────┼───────────┐
       ▼           ▼           ▼
  ┌─────────┐ ┌─────────┐ ┌─────────┐
  │Itinerary│ │ Finances│ │ Packing │
  └────┬────┘ └────┬────┘ └────┬────┘
       │           │           │
       └───────────┼───────────┘
                   ▼
            ┌──────────────┐
            │ Group Chat   │
            │ Text / Media │
            │ Voice Notes  │
            └──────┬───────┘
                   │
                   ▼
            ┌──────────────┐
            │ PDF Report   │
            └──────────────┘

⚙️ Installation

1. Clone the repository

git clone https://github.com/yourusername/tripplanner.git
cd tripplanner

2. Install dependencies

composer install
npm install

3. Configure the environment

Create your environment file and generate an application key:

cp .env.example .env
php artisan key:generate

Configure the required database credentials in .env.

4. Run database migrations

php artisan migrate

5. Link storage

Required for uploaded avatars and chat media:

php artisan storage:link

6. Configure broadcasting

Configure your broadcasting connection and WebSocket credentials in .env.

For Laravel Reverb, the connection can be configured as:

BROADCAST_CONNECTION=reverb

7. Start the application

Run the frontend development server and Laravel server:

# Terminal 1
npm run dev

# Terminal 2
php artisan serve

For Laravel Reverb, also start the WebSocket server:

php artisan reverb:start

📁 Main Functional Areas

TripPlanner
│
├── Itinerary
│   └── Activities & trip planning
│
├── Finances
│   └── Shared and personal expenses
│
├── Packing List
│   └── Collaborative item tracking
│
├── Group Chat
│   ├── Text messages
│   ├── Images
│   ├── Documents
│   └── Voice notes
│
├── User Profiles
│   └── Avatars & travel preferences
│
└── PDF Reports
    └── Offline trip summaries

📜 License

This project is open-source and available under the MIT License.
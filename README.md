TripPlanner ✈️






A real-time, collaborative trip-planning web application built with the TALL stack. TripPlanner helps groups organize itineraries, manage shared and personal expenses, coordinate packing, and communicate through a rich-media chat experience.

Project Status: Active development

📸 Dashboard Preview



Replace the image path above with your actual screenshot path, or remove this section until you have a screenshot ready.

🚀 Core Features

Real-Time Itineraries

Create and manage trip activities.

Add, edit, and reorganize itinerary items.

Keep group members synchronized through real-time updates without requiring manual page refreshes.

Advanced Expense Management

Record group expenses and split them evenly.

Track personal expenses separately.

Automatically calculate who paid what and provide a consolidated financial summary.

Collaborative Packing Lists

Create shared packing lists for each trip.

Assign specific items to group members.

Track packing progress collaboratively.

Rich-Media Group Chat

The built-in trip chat supports:

Text messages

Image uploads with previews

Document attachments such as PDFs

In-browser voice notes

.webm audio recording and uploads using the browser's native Web Audio APIs

PDF Trip Reports

Generate professional multi-page trip reports containing selected trip information such as:

Itinerary

Financial summary

Packing list

PDF reports are generated using DomPDF for offline use and sharing.

User Profiles

Custom user avatars

Travel-style information

Dynamic interface updates powered by Alpine.js

🛠️ Tech Stack

Layer

Technology

Backend

Laravel 11 (PHP)

Frontend

Livewire 3, Alpine.js, Tailwind CSS

Database

MySQL

Real-Time

Laravel Reverb / Pusher WebSockets

PDF Generation

barryvdh/laravel-dompdf

⚙️ Local Installation

1. Clone the repository

git clone https://github.com/yourusername/tripplanner.git
cd tripplanner

2. Install dependencies

Install PHP dependencies:

composer install

Install frontend dependencies:

npm install

3. Configure the environment

Copy the example environment file:

cp .env.example .env

Generate the Laravel application key:

php artisan key:generate

4. Configure the database

Create a MySQL database and update the database credentials in your .env file.

Then run the migrations:

php artisan migrate

5. Link public storage

This is required for uploaded avatars and chat media to be publicly accessible:

php artisan storage:link

6. Configure real-time broadcasting

Configure your broadcasting credentials in .env according to the real-time provider used by the project.

For Laravel Reverb, for example:

BROADCAST_CONNECTION=reverb

Make sure the related Reverb variables are also configured correctly in your environment.

7. Start the application

Run the frontend development server:

npm run dev

In another terminal, start Laravel:

php artisan serve

If Laravel Reverb is enabled, start the WebSocket server in a third terminal:

php artisan reverb:start

📸 Screenshots

Add screenshots to public/screenshots/ and update the paths below.

Dashboard & Itinerary

Expense Management





Rich Media Chat

PDF Report





📂 Suggested Screenshot Structure

public/
└── screenshots/
    ├── dashboard.png
    ├── finances.png
    ├── chat.png
    └── pdf-report.png

🤝 Contributing

Contributions, issues, and feature requests are welcome.

Feel free to open an issue or submit a pull request with improvements.

📝 License

This project is open-source and available under the MIT License.

👨‍💻 About the Project

TripPlanner was built as a practical full-stack application focused on real-time collaboration, group expense management, media uploads, browser-based voice recording, and document generation.

The project demonstrates how Laravel, Livewire, Alpine.js, Tailwind CSS, WebSockets, file storage, and PDF generation can be combined into a single collaborative application.
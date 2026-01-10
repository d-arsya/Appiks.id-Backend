# Appiks.id Backend API - Mental Health Monitoring System

[![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![JWT](https://img.shields.io/badge/Authentication-JWT-000000?style=for-the-badge&logo=json-web-tokens&logoColor=white)](https://jwt.io)
[![Google Gemini](https://img.shields.io/badge/AI-Google%20Gemini-4285F4?style=for-the-badge&logo=google-gemini&logoColor=white)](https://ai.google.dev/)
[![Docker](https://img.shields.io/badge/Deployment-Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)

Appiks.id is a comprehensive backend solution designed for student mental health monitoring and emotional well-being tracking. This RESTful API serves as the backbone for the Appiks ecosystem, providing secure data management, AI-driven insights, and detailed psychological reporting for educational institutions.

## 🚀 Key Modules

### 🧠 Mental Health & Wellness
- **Mood Ecosystem**: Advanced mood tracking with streaks, trends, and history patterns (Weekly/Monthly).
- **Self-Help Suite**: Integrated journaling (Daily & Gratitude), Grounding Techniques, and Sensory Relaxation tools.
- **AI Integration**: Powered by **Google Gemini AI** for analyzing student input and providing intelligent emotional feedback.
- **Educational Content**: Management of therapeutic videos, articles, and mood-synced inspirational quotes.

### 📊 Institutional Analytics
- **Role-Based Dashboards**: Specific data visualizations for Super Admins, Headteachers, Teachers, and Counselors.
- **Mood Analytics**: Real-time mood distribution graphs and school-wide emotional trend analysis.
- **Reporting System**: Formal incident/report management with confirmation, scheduling, and closure workflows.
- **Sharing Platform**: A safe space for student expressions with moderation and reply capabilities.

### 🛡️ Core Infrastructure
- **Role-Based Access Control (RBAC)**: Secure multi-role architecture (Student, Teacher, Counselor, Admin, Super Admin).
- **JWT Authentication**: Robust stateless authentication for mobile and web frontends.
- **Automated Documentation**: Live API documentation powered by **Dedoc Scramble**.
- **Data Operations**: Bulk user management and record exports via Excel (`maatwebsite/excel`).

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / PostgreSQL
- **AI/ML**: Google Gemini PHP SDK
- **Authentication**: JWT Auth (`tymon/jwt-auth`) & Laravel Sanctum
- **Documentation**: Scramble (OpenAPI/Swagger)
- **Deployment**: Docker, Caddy, Supervisor (for Queue/Schedule handling)

## ⚙️ Setup & Development

### Local Requirements
- PHP 8.2+
- Composer
- Docker (optional for containerized setup)

## 🐋 Docker Deployment

The repository includes a production-ready `Dockerfile` and `Caddyfile` configuration.

```bash
docker build -t appiks-backend .
docker run -p 8000:80 appiks-backend
```

## 👨‍💻 Maintainer

**Kamaluddin Arsyad Fadllillah**
*Fullstack & Backend Developer*

- 📧 [arsyadkamaluddin@gmail.com](mailto:arsyadkamaluddin@gmail.com)
- 🔗 [LinkedIn](https://linkedin.com/in/arsyadkamaluddin)
- 🌐 [Portfolio](https://disyfa.space)

---
Developed as part of the mission to improve student mental health through technology.

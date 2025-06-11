# OPDinsight - Hospital Outpatient Department Management System

OPDinsight is a comprehensive web-based management system designed for hospital Outpatient Departments (OPD) to efficiently track and manage consultant activities, patient statistics, and medical procedures.

## Features

### Consultant Management
- **Visiting Consultants**
  - Track visiting consultant schedules and activities
  - Record patient demographics and statistics
  - Monitor foreign patient details
  - Generate daily and monthly reports

- **Resident Consultants**
  - Manage resident consultant activities
  - Track patient statistics and demographics
  - Record foreign patient information
  - Generate comprehensive reports

### Patient Statistics
- Detailed patient demographics tracking
- Race distribution analysis
- Gender-based statistics
- New patient tracking
- Foreign patient management

### Medical Procedures
- Track procedures in New Wing
- Track procedures in Old Wing
- CTG (Cardiotocography) record management
- Comprehensive procedure reporting

### User Management
- Role-based access control
- Staff and admin dashboards
- Secure authentication system
- User activity tracking

## Technical Stack

- **Framework**: Laravel 12
- **Frontend**: 
  - Tailwind CSS
  - Alpine.js
  - Blade Templates
- **Database**: MySQL
- **Authentication**: Laravel Breeze

## Installation

1. Clone the repository
```bash
git clone [repository-url]
cd OPDinsight
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database
- Update `.env` file with your database credentials
- Run migrations and seeders
```bash
php artisan migrate --seed
```

5. Start the development server
```bash
php artisan serve
npm run dev
```

## Usage

1. Access the system through your web browser
2. Log in with your credentials
3. Navigate through the dashboard to access different features
4. Use the sidebar menu to switch between different modules

## Security

- Role-based access control
- Secure password hashing
- CSRF protection
- Input validation
- SQL injection prevention

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is proprietary software. All rights reserved.

## Support

For support, please contact the development team or raise an issue in the repository.

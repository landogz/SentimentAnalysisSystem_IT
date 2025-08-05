# SentimentAnalysisSystem_IT

A comprehensive **Student Feedback and Sentiment Analysis System** built with Laravel 10, featuring an admin dashboard, public survey system, and rule-based sentiment analysis.

## 🚀 Features

### 📊 **Admin Dashboard**
- **Real-time Statistics** - Total surveys, teachers, subjects, average ratings
- **Sentiment Analytics** - Positive/Negative/Neutral breakdown with charts
- **Top Performers** - Best rated teachers and subjects
- **Recent Activity** - Latest survey submissions
- **Monthly Trends** - Survey activity over time

### 👥 **Teacher Management**
- **CRUD Operations** - Add, Edit, Delete, View teachers
- **Department Assignment** - Organize teachers by departments
- **Contact Information** - Email, phone, bio management
- **Status Control** - Active/Inactive teacher status with proper validation
- **Performance Metrics** - Average ratings and survey counts

### 📚 **Subject Management**
- **Multi-Teacher Support** - Assign multiple teachers to subjects
- **Primary Teacher** - Designate primary instructor with star icon
- **Subject Details** - Code, name, description management
- **Status Control** - Active/Inactive subject status with proper validation
- **Performance Analytics** - Ratings and sentiment statistics
- **Advanced Filtering** - Search by teacher, status, and text

### 📝 **Public Survey System**
- **Star Rating System** - 1.0 to 5.0 decimal ratings
- **Dynamic Selection** - Teacher and subject dropdowns
- **Feedback Text** - Open-ended feedback collection
- **AJAX Submission** - Smooth, non-refreshing form submission
- **Sentiment Analysis** - Automatic sentiment classification

### 🧠 **Sentiment Analysis Engine**
- **Rule-based Analysis** - Keyword matching system
- **Three Categories** - Positive, Negative, Neutral
- **Customizable Keywords** - Easy to modify sentiment rules
- **Real-time Processing** - Instant sentiment classification

### 📈 **Reporting & Analytics**
- **Filterable Reports** - By teacher, subject, date range
- **Export Options** - PDF and Excel export capabilities
- **Sentiment Breakdown** - Detailed sentiment statistics
- **Rating Analytics** - Average ratings and trends

### 🔐 **Authentication & Security**
- **Laravel Breeze** - Built-in authentication system
- **Email Verification** - Secure account verification
- **Password Reset** - Forgot password functionality
- **Protected Routes** - Middleware-based access control

## 🛠️ Technology Stack

- **Backend**: Laravel 10+ (PHP 8.1+)
- **Frontend**: Bootstrap 5, AdminLTE 3
- **Database**: MySQL 8.0+
- **JavaScript**: jQuery, AJAX
- **Charts**: Chart.js
- **Notifications**: SweetAlert2
- **Authentication**: Laravel Breeze

## 📋 Requirements

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **MySQL**: 8.0 or higher
- **Node.js**: 16+ (for asset compilation)
- **Web Server**: Apache/Nginx

## 🚀 Installation

### 1. **Clone the Repository**
```bash
git clone https://github.com/landogz/SentimentAnalysisSystem_IT.git
cd SentimentAnalysisSystem_IT
```

### 2. **Install Dependencies**
```bash
composer install
npm install
```

### 3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

### 4. **Database Configuration**
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentiment_analysis_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. **Run Migrations & Seeders**
```bash
php artisan migrate --seed
```

### 6. **Start Development Server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 👤 Default Admin Account

After running the seeders, you can log in with:
- **Email**: `admin@example.com`
- **Password**: `password`

## 📖 Usage Guide

### 🔐 **Admin Login**
1. Navigate to `/login`
2. Use the default admin credentials
3. Access the dashboard at `/dashboard`

### 👥 **Managing Teachers**
1. Go to **Teachers** in the sidebar
2. **Add Teacher**: Click "Add Teacher" button
3. **Edit Teacher**: Click edit icon on any teacher row
4. **Delete Teacher**: Click delete icon (only if no surveys exist)
5. **View Details**: Click view icon for detailed information
6. **Status Updates**: Toggle Active/Inactive status with proper validation

### 📚 **Managing Subjects**
1. Go to **Subjects** in the sidebar
2. **Add Subject**: Click "Add Subject" button
3. **Assign Teachers**: Select multiple teachers, designate primary
4. **Edit Subject**: Click edit icon to modify details (assigned teachers are pre-checked)
5. **View Details**: Click view icon for subject analytics
6. **Advanced Search**: Use filters by teacher, status, or text search

### 📝 **Public Survey**
1. Navigate to `/survey` (public access)
2. Select teacher from dropdown
3. Select subject (filtered by teacher)
4. Provide star rating (1.0-5.0)
5. Add feedback text (optional)
6. Submit survey

### 📊 **Viewing Reports**
1. Go to **Reports** in the sidebar
2. Apply filters (teacher, subject, date range)
3. View sentiment breakdown
4. Export data if needed

## 🏗️ Project Structure

```
SentimentAnalysisSystem/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/            # Sample data
├── resources/
│   └── views/              # Blade templates
├── routes/                 # Application routes
└── config/                 # Configuration files
```

## 🔧 Key Features Explained

### 🧠 **Sentiment Analysis**
The system uses a rule-based approach with predefined keyword lists:
- **Positive Words**: excellent, great, amazing, good, etc.
- **Negative Words**: terrible, awful, bad, poor, etc.
- **Neutral Words**: okay, fine, average, etc.

### 👥 **Teacher-Subject Relationship**
- **Many-to-Many**: Multiple teachers can teach one subject
- **Primary Teacher**: One teacher marked as primary (star icon)
- **Flexible Assignment**: Easy to add/remove teachers from subjects

### 📊 **Rating System**
- **Decimal Support**: Ratings from 1.0 to 5.0
- **Star Interface**: Visual star rating selector
- **Average Calculations**: Automatic average rating computation

### 🔍 **Advanced Search & Filtering**
- **Text Search**: Search subjects by code or name
- **Teacher Filter**: Filter subjects by assigned teachers
- **Status Filter**: Filter by active/inactive status
- **Combined Filters**: Use multiple filters simultaneously

### 📄 **Custom Pagination**
- **Bootstrap 5 Styling**: Professional pagination design
- **Responsive Layout**: Works on all screen sizes
- **Results Counter**: Shows current range and total
- **Navigation Controls**: Previous/Next with page numbers

## 🔒 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive server-side validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping
- **Authentication**: Secure login/logout system

## 📱 Responsive Design

- **Mobile-First**: Bootstrap 5 responsive design
- **AdminLTE Theme**: Professional admin interface
- **Touch-Friendly**: Optimized for mobile devices
- **Cross-Browser**: Compatible with all modern browsers

## 🚀 Deployment

### **Shared Hosting**
1. Upload files to web server
2. Set document root to `public/`
3. Configure database in `.env`
4. Run `php artisan migrate --seed`

### **VPS/Dedicated Server**
1. Clone repository to server
2. Install dependencies: `composer install --optimize-autoloader --no-dev`
3. Set proper permissions
4. Configure web server (Apache/Nginx)
5. Set up SSL certificate

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Support

For support and questions:
- **Email**: [Your Email]
- **Issues**: [GitHub Issues](https://github.com/landogz/SentimentAnalysisSystem_IT/issues)

## 🔄 Version History

- **v1.0.0** - Initial release with core features
- **v1.1.0** - Added pagination fixes and UI improvements
- **v1.2.0** - Enhanced sentiment analysis and reporting
- **v1.3.0** - Fixed CRUD operations and search functionality
  - ✅ Fixed assigned teachers not checked in edit modal
  - ✅ Fixed is_active validation errors for checkboxes
  - ✅ Fixed teacher status updates
  - ✅ Fixed subject search and filtering
  - ✅ Added comprehensive debugging and error handling
  - ✅ Enhanced pagination with Bootstrap 5 styling
  - ✅ Improved form validation and user feedback

## 🐛 Recent Bug Fixes

### **✅ CRUD Operations**
- **Edit Modal**: Assigned teachers now properly checked when editing subjects
- **Form Validation**: Fixed checkbox validation errors for active/inactive status
- **Status Updates**: Teacher and subject status changes now work correctly
- **Error Handling**: Enhanced error messages and debugging information

### **✅ Search & Filtering**
- **Teacher Filter**: Fixed subject filtering by assigned teachers
- **Text Search**: Improved search functionality for subjects
- **Combined Filters**: Multiple filters now work together seamlessly
- **Real-time Updates**: Filters apply immediately without page refresh

### **✅ UI/UX Improvements**
- **Pagination**: Custom Bootstrap 5 pagination with proper styling
- **Form Feedback**: Better validation messages and user notifications
- **Debug Information**: Console logs for troubleshooting
- **Responsive Design**: Improved mobile compatibility

---

**Built with ❤️ using Laravel 10**
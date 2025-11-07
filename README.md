# PRMSU CCIT Student Feedback & Sentiment Analysis System

A modern, professional Laravel-based system for collecting and analyzing student feedback with advanced sentiment analysis capabilities. Built with a beautiful, responsive design and comprehensive reporting features.

## 🆕 Recent Updates & Features

### **Latest Enhancements (v3.2)**
- **Enhanced Button Visibility**: Improved color-coded buttons for better user experience
- **CCIT-Specific Part 2 Questions**: Updated to 5 targeted CCIT difficulty assessment questions
- **Performance-Based Color Scheme**: Part 1 buttons use performance gradient (Green to Red)
- **Difficulty-Based Color Scheme**: Part 2 buttons use difficulty gradient (Red to Green)
- **Enhanced Accessibility**: Better contrast and visual feedback for all rating buttons
- **Updated Survey Seeder**: Compatible with new Part 2 structure and question count

### **Previous Enhancements (v3.1)**
- **Part-Specific Survey Analysis**: Comprehensive 3-part survey structure with detailed analysis
- **Professional Tabbed Modal**: Enhanced survey responses viewer with tabbed interface
- **Advanced Sentiment Scoring**: Part 3 comments converted to numerical scores (1-5 scale)
- **Enhanced Survey Seeder**: Realistic part-specific data with sentiment-based responses
- **Improved Survey Responses**: Professional modal with overview, part breakdown, and analysis
- **Database-Driven Sentiment Words**: Enhanced negation support and multi-language analysis
- **Smart Part 3 Calculation**: Sentiment analysis converted to meaningful numerical ratings

### **Previous Features (v3.0)**
- **Enhanced Sentiment Analysis**: Database-driven sentiment words with scoring system
- **Multi-language Support**: English and Tagalog sentiment analysis
- **Translation Integration**: Google Translate API for automatic language translation
- **Advanced CRUD Management**: Complete sentiment words management interface
- **Real-time Analysis Testing**: Built-in sentiment analysis testing tool
- **Enhanced Authentication System**: Improved login, register, and password reset flows
- **Advanced Survey Management**: Real-time form validation and AJAX submissions
- **Dynamic Survey Questions CRUD**: Complete management system for survey questions with option and comment types
- **Survey Responses Viewer**: Clickable survey rows with detailed response modal on teacher and subject pages
- **Automatic Rating Calculation**: Smart rating calculation based on option questions (70%) and sentiment analysis (30%)
- **Comprehensive Reporting**: Multi-format export capabilities (PDF, Excel, CSV)
- **Mobile-First Design**: Fully responsive interface optimized for all devices
- **Session Management**: 30-minute timeout with SweetAlert notifications
- **DataTables Integration**: Advanced table features with search, sort, and pagination

### **Part-Specific Survey Structure**
- **Part 1: Instructor Evaluation**: 20 questions with 1-5 rating scale (Outstanding to Poor)
- **Part 2: Difficulty Level**: 5 CCIT-specific questions with 1-5 rating scale (Very Difficult to Very Not Difficult)
- **Part 3: Open Comments**: 4 open-ended questions with sentiment analysis
- **Professional Tabbed Interface**: Overview, Part 1, Part 2, Part 3 tabs with detailed analysis
- **Sentiment Score Conversion**: Part 3 comments converted to 1-5 numerical scale
- **Comprehensive Analysis**: Part-specific averages and overall sentiment analysis

### **Enhanced Survey Responses Modal**
- **Professional Design**: Modern tabbed interface with color-coded sections
- **Overview Tab**: Summary cards with part-specific scores and sentiment analysis
- **Part-Specific Tabs**: Detailed breakdown of each survey part
- **Visual Analysis**: Color-coded cards with icons and scores
- **Responsive Layout**: Works perfectly on desktop and mobile devices
- **Interactive Elements**: Hover effects and smooth transitions

### **Enhanced Button Styling & Visibility**
- **Part 1 Color Scheme**: Performance-based gradient (Green → Light Blue → Gray → Orange → Red)
  - 5 (Outstanding): Green - represents excellent performance
  - 4 (Very Satisfactory): Light Blue - represents very good performance
  - 3 (Satisfactory): Gray - represents acceptable performance
  - 2 (Fair): Orange - represents below average performance
  - 1 (Poor): Red - represents poor performance
- **Part 2 Color Scheme**: Difficulty-based gradient (Red → Orange → Gray → Light Blue → Green)
  - 5 (Very Difficult): Red - represents highest difficulty
  - 4 (Difficult): Orange - represents high difficulty
  - 3 (Slightly Difficult): Gray - represents medium difficulty
  - 2 (Not Difficult): Light Blue - represents low difficulty
  - 1 (Very Not Difficult): Green - represents lowest difficulty
- **Enhanced Visibility**: Thicker borders (2px), bolder text (600 weight), improved contrast
- **Interactive States**: Hover effects with solid color fills and smooth transitions
- **Selected States**: Clear visual feedback with solid colors and white text
- **Accessibility**: Better color contrast for users with visual impairments

### **Sentiment Analysis Features**
- **Database-Driven Words**: 209 pre-seeded sentiment words with scores
- **Multi-language Support**: English (96 words) and Tagalog (113 words)
- **Advanced Scoring**: -5.0 to 5.0 scoring system with detailed breakdown
- **Rating Calculation**: Automatic 1-5 star rating conversion
- **Translation Support**: Automatic Tagalog to English translation
- **Real-time Testing**: Built-in analysis testing with custom text
- **Statistics Dashboard**: Comprehensive word counts and distribution
- **Filtering System**: Filter by type, language, and status
- **CRUD Operations**: Complete Create, Read, Update, Delete functionality
- **Negation Support**: Advanced negation handling for improved accuracy

### **Performance Improvements**
- **AJAX-Powered Interface**: No page reloads for better user experience
- **Optimized Database Queries**: Efficient data loading and caching
- **Lazy Loading**: Progressive data loading for better performance
- **Asset Optimization**: Minified CSS/JS for production deployment

## 🧠 Sentiment Analysis System

### **Advanced Sentiment Analysis**
- **Database-Driven Words**: Flexible sentiment word management with scores
- **Multi-language Support**: English and Tagalog sentiment analysis
- **Translation Integration**: Google Translate API for automatic translation
- **Advanced Scoring**: Weighted scoring system with detailed breakdown
- **Rating Calculation**: Converts sentiment scores to 1-5 star ratings
- **Real-time Testing**: Built-in sentiment analysis testing tool
- **Statistics Dashboard**: Comprehensive analytics and reporting
- **Negation Handling**: Advanced negation word processing for improved accuracy

### **Part 3 Comments Analysis**
- **Sentiment Score Conversion**: Comments converted to 1-5 numerical scale
- **Intensity-Based Scoring**: Stronger sentiments get higher/lower scores
- **Positive Sentiment**: 4.5/5.0 (can reach 5.0 for very strong positive)
- **Neutral Sentiment**: 3.0/5.0 (baseline neutral score)
- **Negative Sentiment**: 1.5/5.0 (can reach 1.0 for very strong negative)
- **Intensity Adjustment**: ±0.5 based on sentiment strength
- **Consistent Scale**: All parts now use 1-5 rating scale for comparison

### **Sentiment Words Management**
- **Complete CRUD Interface**: Full Create, Read, Update, Delete operations
- **Filtering System**: Filter by type (positive/negative/neutral), language, status
- **Statistics Dashboard**: Real-time word counts and distribution
- **Test Analysis Tool**: Built-in sentiment testing with custom text
- **Floating Action Button**: Quick access to test analysis
- **Responsive Design**: Mobile-optimized management interface
- **Negation Pairs**: Support for opposite word relationships

### **Database Schema**
```sql
CREATE TABLE sentiment_words (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    word VARCHAR(255) NOT NULL,
    negation VARCHAR(255) NULL,
    type ENUM('positive', 'negative', 'neutral') NOT NULL,
    score DECIMAL(3,1) DEFAULT 1.0,
    language VARCHAR(10) DEFAULT 'en',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_word_language (word, language),
    INDEX idx_type_language (type, language),
    INDEX idx_negation (negation)
);
```

### **API Endpoints**
- `GET /sentiment-words` - List words with filters
- `POST /sentiment-words` - Add new word
- `PUT /sentiment-words/{id}` - Update word
- `DELETE /sentiment-words/{id}` - Delete word
- `POST /sentiment-words/test-analysis` - Test analysis
- `GET /sentiment-words/statistics` - Get statistics

### **Sample Data**
The system comes pre-loaded with:
- **English Words (96)**: excellent (5.0), great (3.0), terrible (-5.0), etc.
- **Tagalog Words (113)**: maganda (2.0), mahusay (2.5), masama (-2.0), etc.
- **Negation Pairs**: beautiful → ugly, good → bad, excellent → terrible

## 🎨 Modern Design Features

### **Professional Color Scheme**
- **Dark Gray (#494850)**: Primary background and text
- **Light Green (#8FCFA8)**: Success states and positive elements
- **Coral Pink (#F16E70)**: Error states and negative elements
- **Golden Orange (#F5B445)**: Warning states and neutral elements
- **Light Blue (#98AAE7)**: Primary actions and highlights

### **Enhanced User Interface**
- **Google Fonts Integration**: Poppins font family for modern typography
- **Gradient Backgrounds**: Professional gradient effects throughout
- **Rounded Corners**: Modern 8-12px border radius on all components
- **Smooth Animations**: CSS transitions and hover effects
- **Professional Cards**: Enhanced card styling with shadows and hover effects
- **Tabbed Interfaces**: Professional tab navigation with smooth transitions

## 📊 Dashboard & Analytics

### **Interactive Dashboard**
- **Statistics Cards**: Real-time data with custom color coding
- **Sentiment Analysis Pie Chart**: Interactive Chart.js visualization
- **Equal Height Cards**: Responsive flexbox layout
- **Top Teachers & Subjects**: Performance rankings with ratings
- **Monthly Trends**: Dynamic chart showing survey trends over time
- **Recent Activity**: Latest surveys with teacher and subject information

## 📝 Survey Questions Management

### **Dynamic CRUD System**
- **Question Types**: Support for option questions (1-5 scale) and comment questions (text input)
- **Part Categorization**: Questions organized into Part 1, Part 2, Part 3
- **Order Management**: Flexible question ordering with drag-and-drop capability
- **Active/Inactive Toggle**: Easy activation/deactivation of questions
- **AJAX Operations**: Smooth create, read, update, delete operations without page reloads
- **SweetAlert Notifications**: Professional success/error messages
- **Responsive Design**: Mobile-friendly interface for question management

### **Enhanced Survey Responses Viewer**
- **Professional Tabbed Modal**: Modern interface with overview and part-specific tabs
- **Overview Tab**: Summary cards showing part-specific scores and sentiment analysis
- **Part 1 Tab**: Instructor evaluation responses with star ratings
- **Part 2 Tab**: Difficulty level responses with visual indicators
- **Part 3 Tab**: Open comments with sentiment analysis results
- **Color-Coded Sections**: Blue (Part 1), Info (Part 2), Green (Part 3)
- **Interactive Elements**: Hover effects and smooth transitions
- **Responsive Design**: Works perfectly on all device sizes

### **Part-Specific Analysis**
- **Part 1 Analysis**: Instructor evaluation average (1-5 scale)
- **Part 2 Analysis**: CCIT difficulty level average (1-5 scale)
  - **CCIT-Specific Questions**: 5 targeted questions about CCIT subject difficulty
  - **Question Topics**: Understanding concepts, assignments/projects, quizzes/exams, practical activities, lesson pace
  - **Difficulty Assessment**: Comprehensive evaluation of CCIT learning experience
- **Part 3 Analysis**: Sentiment score conversion (1-5 scale)
- **Overall Analysis**: Combined rating with sentiment breakdown
- **Visual Indicators**: Color-coded cards with icons and scores
- **Detailed Breakdown**: Individual question responses with ratings

### **Smart Rating Calculation**
- **Automatic Algorithm**: Calculates overall rating from multiple data sources
- **Option Questions Weight**: 70% weight from 1-5 scale question responses
- **Sentiment Analysis Weight**: 30% weight from text sentiment analysis
- **Sentiment Conversion**: Positive (4.5), Neutral (3.0), Negative (1.5) ratings
- **Range Validation**: Ensures final rating stays within 1.0-5.0 range
- **Precision Control**: Rounds to 1 decimal place for consistency
- **Part-Specific Averages**: Individual averages for each survey part

### **Advanced Reporting System**
- **Multi-Format Export**: PDF, Excel, and CSV export capabilities
- **Filtered Reports**: By teacher, subject, and date range
- **Interactive Charts**: Sentiment distribution and rating analysis
- **Real-time Statistics**: Dynamic data updates with AJAX
- **Rating Distribution**: Visual breakdown of survey ratings
- **Filtered Statistics**: Real-time stats based on selected filters

## 🔧 Technical Features

### **Export Functionality**
- **CSV Export**: Fully functional with proper headers and data formatting
- **PDF Export**: Ready for DomPDF implementation
- **Excel Export**: Ready for PhpSpreadsheet implementation
- **Filtered Data**: Respects all applied filters (teacher, subject, date)
- **Statistics Included**: Summary statistics in all exports
- **Custom Headers**: Professional export formatting with proper column names

### **Mobile-First Design**
- **Responsive Layout**: Optimized for all screen sizes
- **Touch-Friendly**: Enhanced touch targets for mobile devices
- **Horizontal Scrolling**: Smooth table scrolling on mobile
- **iOS Zoom Prevention**: Prevents unwanted zoom on form inputs
- **Custom Scrollbars**: Styled scrollbars for better UX
- **Mobile Sidebar**: Full-screen overlay for mobile navigation

### **Session Management**
- **30-Minute Timeout**: Automatic session expiration detection
- **SweetAlert Notifications**: Professional session expiration alerts
- **CSRF Protection**: Built-in Laravel CSRF token handling
- **Graceful Error Handling**: User-friendly error messages
- **Auto-Reset Timer**: Session timer resets on user activity

## 🎯 User Experience

### **Professional Sidebar**
- **Prominent Logo**: Large 150px logo at the top of sidebar
- **PRMSU CCIT Branding**: Consistent branding throughout
- **Clean Navigation**: Minimalist menu with hover effects
- **Active State Indicators**: Clear visual feedback for current page
- **Collapsible Design**: Smooth mobile sidebar functionality
- **Sentiment Words Menu**: Easy access to sentiment analysis management

### **Enhanced Navigation**
- **Professional Navbar**: Gradient backgrounds and modern styling
- **Collapsible Sidebar**: Smooth mobile sidebar functionality
- **Breadcrumb Navigation**: Clear page hierarchy
- **Quick Actions**: Public survey access from navbar
- **Mobile Overlay**: Full-screen overlay for mobile navigation

### **Form & Data Management**
- **AJAX Submissions**: No page reloads for better UX
- **SweetAlert Notifications**: Professional success/error messages
- **DataTables Integration**: Advanced table features with search/sort
- **Real-time Validation**: Client-side form validation
- **Dynamic Dropdowns**: Teacher-subject relationship management
- **Form Persistence**: Maintains form data during validation

## 📱 Mobile Optimizations

### **Touch-Friendly Interface**
- **Minimum Touch Targets**: 44px minimum for all interactive elements
- **Smooth Scrolling**: `-webkit-overflow-scrolling: touch` for iOS
- **Custom Scrollbars**: Styled scrollbars for better mobile UX
- **Responsive Tables**: Horizontal scrolling with minimum widths
- **Touch Gestures**: Proper touch event handling

### **Mobile-Specific Features**
- **Sidebar Overlay**: Full-screen overlay for mobile sidebar
- **Touch Gestures**: Proper touch event handling
- **Viewport Optimization**: Prevents zoom on form inputs
- **Mobile-First CSS**: Progressive enhancement approach
- **Body Scroll Prevention**: Prevents background scrolling when sidebar is open

## 🚀 Installation & Setup

### **Prerequisites**
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx)

### **Installation Steps**

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd SentimentAnalysisSystem
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   ```bash
   # Update .env with your database credentials
   php artisan migrate
   php artisan db:seed
   # Seed survey questions with part structure
   php artisan db:seed --class=SurveyQuestionSeeder
   # Seed sample surveys with part-specific data
   php artisan db:seed --class=SurveySeeder
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

### **Sample Data**
Access `/add-sample-data` to populate the system with sample teachers, subjects, and surveys for testing.

### **Google Translate API Setup**
For translation features, add to your `.env` file:
```env
GOOGLE_TRANSLATE_API_KEY=your_api_key_here
```

### **Production Deployment**
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 📋 System Requirements

### **Backend**
- Laravel 10.x
- PHP 8.1+
- MySQL 8.0+ or MariaDB 10.5+
- Composer 2.0+

### **Frontend**
- Bootstrap 5.3
- jQuery 3.6
- Chart.js 4.x
- SweetAlert2 11.x
- Font Awesome 6.x
- DataTables 1.13+

### **Browser Support**
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### **Dependencies**
- **Laravel DomPDF**: For PDF export functionality
- **Maatwebsite Excel**: For Excel export functionality
- **Laravel Sanctum**: For API authentication
- **Guzzle HTTP**: For HTTP client functionality

## 🎨 Design System

### **Typography**
- **Primary Font**: Poppins (Google Fonts)
- **Fallback**: Source Sans Pro, sans-serif
- **Font Weights**: 400 (Regular), 500 (Medium), 600 (Semi-bold), 700 (Bold)

### **Spacing System**
- **Base Unit**: 8px
- **Padding**: 15px, 20px, 25px
- **Margins**: 8px, 15px, 20px, 50px
- **Border Radius**: 6px, 8px, 12px

### **Component Styling**
- **Cards**: 12px border radius, subtle shadows
- **Buttons**: 6px border radius, hover animations
- **Forms**: Consistent styling with focus states
- **Tables**: Rounded corners, hover effects
- **Tabs**: Professional tab navigation with smooth transitions

## 🔒 Security Features

- **CSRF Protection**: Laravel built-in CSRF tokens
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping
- **Authentication Middleware**: Secure route protection
- **Password Hashing**: Bcrypt password encryption

## 📈 Performance Optimizations

- **Lazy Loading**: Efficient data loading with AJAX
- **Caching**: Laravel cache implementation ready
- **Image Optimization**: Compressed logo and images
- **Minified Assets**: Production-ready asset compilation
- **Database Indexing**: Optimized database queries
- **Route Caching**: Optimized route loading
- **Config Caching**: Optimized configuration loading

## 🧪 Testing

### **Test Coverage**
- **Feature Tests**: Authentication, CRUD operations
- **Unit Tests**: Model relationships and business logic
- **Browser Tests**: User interface testing
- **Database Tests**: Data integrity and migrations

### **Running Tests**
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### **Development Guidelines**
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions, please contact the development team or create an issue in the repository.

### **Common Issues**
- **Session Timeout**: System automatically logs out after 30 minutes of inactivity
- **Export Issues**: Ensure proper file permissions for export functionality
- **Mobile Display**: Use responsive design features for optimal mobile experience
- **Translation Issues**: Ensure Google Translate API key is configured for translation features
- **Part 3 Analysis**: Sentiment analysis requires sentiment words to be seeded in database

---

**PRMSU CCIT Student Feedback & Sentiment Analysis System** - Empowering educational institutions with modern feedback collection and analysis tools.

*Version 3.1 - Enhanced with part-specific survey analysis, professional tabbed modal interface, advanced sentiment scoring, and comprehensive survey responses viewer.*

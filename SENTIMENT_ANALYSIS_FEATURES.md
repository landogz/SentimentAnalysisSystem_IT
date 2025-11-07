# Enhanced Sentiment Analysis System

This document describes the enhanced sentiment analysis system that includes database-driven words with scores, translation support, and advanced scoring mechanisms.

## 🚀 New Features

### 1. Database-Driven Sentiment Words
- **SentimentWords Table**: Stores words with their sentiment type, score, and language
- **Flexible Scoring**: Each word has a score from -5.0 to 5.0
- **Multi-language Support**: Supports English (en) and Tagalog (tl)
- **Active/Inactive Status**: Words can be enabled or disabled

### 2. Translation Support
- **Google Translate API Integration**: Automatically translates text before analysis
- **Fallback Mechanism**: If translation fails, analyzes original text
- **Language Detection**: Supports multiple source languages

### 3. Advanced Scoring System
- **Weighted Scoring**: Different words have different impact scores
- **Rating Calculation**: Converts sentiment scores to 1-5 star ratings
- **Detailed Analysis**: Provides positive, negative, and neutral scores separately

### 4. Management Interface
- **Web-based Management**: Full CRUD operations for sentiment words
- **Real-time Testing**: Test sentiment analysis with custom text
- **Statistics Dashboard**: View word counts and distribution
- **AJAX Integration**: No page reloads, uses SweetAlert for notifications

## 📊 Database Schema

### SentimentWords Table
```sql
CREATE TABLE sentiment_words (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    word VARCHAR(255) NOT NULL,
    type ENUM('positive', 'negative', 'neutral') NOT NULL,
    score DECIMAL(3,1) DEFAULT 1.0,
    language VARCHAR(10) DEFAULT 'en',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_word_language (word, language),
    INDEX idx_type_language (type, language)
);
```

## 🔧 API Endpoints

### Sentiment Words Management
- `GET /sentiment-words` - List words with filters
- `POST /sentiment-words` - Add new word
- `PUT /sentiment-words/{id}` - Update word
- `DELETE /sentiment-words/{id}` - Delete word
- `POST /sentiment-words/test-analysis` - Test analysis
- `GET /sentiment-words/statistics` - Get statistics

### Test Routes
- `GET /test-sentiment` - Test sentiment analysis with sample data

## 💡 Usage Examples

### 1. Basic Sentiment Analysis
```php
$sentimentService = new SentimentAnalysisService();

// Analyze English text
$result = $sentimentService->analyzeSentimentWithScore("I love this teacher!", 'en');
echo "Sentiment: " . $result['sentiment']; // positive
echo "Score: " . $result['score']; // 3.0
echo "Rating: " . $result['rating']; // 3.6/5
```

### 2. Tagalog Text Analysis
```php
// Analyze Tagalog text directly
$result = $sentimentService->analyzeSentimentWithScore("Gusto ko ang serbisyo", 'tl');
echo "Sentiment: " . $result['sentiment']; // positive
```

### 3. Translation + Analysis
```php
// Translate Tagalog to English then analyze
$result = $sentimentService->analyzeSentimentWithTranslation(
    "Gusto ko ang serbisyo, pero mabagal ang delivery.", 
    'tl', 
    'en'
);
echo "Translated: " . $result['translated_text'];
echo "Sentiment: " . $result['sentiment'];
```

### 4. Adding Custom Words
```php
// Add a new positive word
$sentimentService->addSentimentWord('fantastic', 'positive', 3.0, 'en');

// Update word score
$sentimentService->updateSentimentWordScore('good', 2.5, 'en');
```

## 🎯 Sample Data

The system comes pre-loaded with:

### English Words (96 words)
- **Positive**: excellent (5.0), great (3.0), good (1.0), amazing (3.0), love (3.0), etc.
- **Negative**: terrible (-5.0), bad (-3.0), poor (-1.0), awful (-3.0), hate (-3.0), etc.
- **Neutral**: okay (0.0), fine (0.5), average (0.0), normal (0.0), etc.

### Tagalog Words (113 words)
- **Positive**: maganda (2.0), mahusay (2.5), gusto (2.0), mahal (2.5), etc.
- **Negative**: masama (-2.0), pangit (-2.0), mabagal (-1.5), ayaw (-2.0), etc.
- **Neutral**: sige (0.5), pwede (0.5), etc.

## 🔧 Configuration

### Google Translate API
Add to your `.env` file:
```env
GOOGLE_TRANSLATE_API_KEY=your_api_key_here
```

### Services Configuration
The API key is configured in `config/services.php`:
```php
'google' => [
    'translate_api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
],
```

## 📈 Analysis Results

The enhanced system provides detailed analysis results:

```php
[
    'sentiment' => 'positive',
    'score' => 8.0,
    'positive_score' => 8.0,
    'negative_score' => 0.0,
    'neutral_score' => 0.0,
    'rating' => 4.6,
    'matched_words' => [
        ['word' => 'love', 'score' => 3.0, 'type' => 'positive'],
        ['word' => 'excellent', 'score' => 3.0, 'type' => 'positive'],
        ['word' => 'helpful', 'score' => 2.0, 'type' => 'positive']
    ],
    'original_text' => 'i love this teacher, they are excellent and very helpful!',
    'translated_text' => null,
    'translation_success' => false
]
```

## 🎨 Web Interface Features

### Management Dashboard
- **Filter by Type**: Positive, Negative, Neutral
- **Filter by Language**: English, Tagalog
- **Filter by Status**: Active, Inactive
- **Real-time Statistics**: Word counts and distribution

### Test Analysis Tool
- **Custom Text Input**: Test any text for sentiment
- **Language Selection**: Choose source language
- **Translation Option**: Enable/disable translation
- **Detailed Results**: View scores, ratings, and matched words

### AJAX Integration
- **No Page Reloads**: All operations use AJAX
- **SweetAlert Notifications**: Beautiful success/error messages
- **Real-time Updates**: Statistics and tables update automatically

## 🚀 Getting Started

1. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

2. **Seed the Database**:
   ```bash
   php artisan db:seed --class=SentimentWordSeeder
   ```

3. **Test the System**:
   ```bash
   php example_sentiment_analysis.php
   ```

4. **Access Web Interface**:
   - Navigate to `/sentiment-words` (requires authentication)
   - Use the management interface to add/edit words
   - Test sentiment analysis with custom text

## 🔍 Example Output

```
=== Enhanced Sentiment Analysis System ===

1. English Text Analysis:
Original Text: i love this teacher, they are excellent and very helpful!
Sentiment: positive
Score: 10
Rating: 5.0/5
Positive Score: 10
Negative Score: 0
Neutral Score: 0
Matched Words:
  - love (3.0) [positive]
  - excellent (5.0) [positive]
  - helpful (2.0) [positive]

2. Tagalog Text Analysis (Direct):
Original Text: gusto ko ang serbisyo, pero mabagal ang delivery.
Sentiment: positive
Score: 0.5
Rating: 3.1/5
Positive Score: 2
Negative Score: -1.5
Neutral Score: 0
Matched Words:
  - gusto (2.0) [positive]
  - mabagal (-1.5) [negative]
```

## 🎯 Key Improvements

1. **Database-Driven**: No more hardcoded word lists
2. **Multi-language**: Support for English and Tagalog
3. **Translation**: Automatic translation for better analysis
4. **Scoring System**: Weighted scoring with detailed breakdown
5. **Rating Calculation**: Converts scores to 1-5 star ratings
6. **Web Management**: Full CRUD interface for managing words
7. **Real-time Testing**: Test analysis with custom text
8. **Statistics**: Comprehensive analytics and reporting

This enhanced system provides a robust, scalable, and user-friendly sentiment analysis solution that can handle multiple languages and provide detailed insights into text sentiment. 
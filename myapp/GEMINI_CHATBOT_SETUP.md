# Google Gemini AI Setup Guide

## ✅ Complete AI Integration

Your Laravel application now has comprehensive Google Gemini AI integration including:
1. **AI Chatbot** - Customer-facing chat assistant
2. **AI Content Generator** - Auto-generate tours and blog posts with SEO and itineraries

---

## 🔑 Step 1: Get Your Gemini API Key

1. Go to [Google AI Studio](https://aistudio.google.com/apikey)
2. Sign in with your Google account
3. Click **"Create API Key"**
4. Copy the generated API key

## ⚙️ Step 2: Configure Your Environment

Open your `.env` file and update the `GEMINI_API_KEY` value:

```env
# Google Gemini AI API Key
GEMINI_API_KEY=your_actual_api_key_here
```

**Important:** Replace `your_actual_api_key_here` with your real API key.

## 🚀 Step 3: Clear Configuration Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## 🎯 Step 4: Test the Features

### Test the Chatbot
1. Start your server: `php artisan serve`
2. Open your website
3. Click the floating chat button (bottom-right)
4. Ask questions about tours, pricing, destinations

### Test AI Content Generator

#### For Tours:
1. Log into the admin panel (`/admin`)
2. Go to **Tours** section
3. Click **"Create Tour"** or edit an existing tour
4. Fill in the basic information:
   - **Title** (required)
   - **Destination** (optional but recommended for better results)
   - **Duration** (e.g., "5 days" - used for itinerary generation)
5. Scroll down to the **Content** section
6. Click the **"✨ Generate All Content with AI"** button at the top of the Content section
7. Confirm the generation in the modal dialog
8. The AI will auto-fill:
   - Excerpt
   - Content (full description)
   - Itinerary (day-by-day activities)
   - Highlights
   - Included/Excluded services
   - SEO meta data
9. Review and edit the AI-generated content
10. Save or publish the tour

#### For Blog Posts:
1. Log into the admin panel (`/admin`)
2. Go to **Blog Posts** section
3. Click **"Create Blog"** or edit an existing post
4. Fill in the basic information:
   - **Title** (required)
   - **Category** (optional but recommended for better results)
5. Scroll down to the **Content** section
6. Click the **"✨ Generate All Content with AI"** button at the top of the Content section
7. Confirm the generation in the modal dialog
8. The AI will auto-fill:
   - Excerpt
   - Content (full article with HTML headings)
   - Highlights
   - SEO meta data
9. Review and edit the AI-generated content
10. Save or publish the blog post

---

## 📋 Feature Overview

### 1. AI Chatbot (Customer-Facing)

**Location:** Floating widget on all pages

**Capabilities:**
- Answers questions about tours and destinations
- Provides pricing information
- Explains booking process
- Shares contact details
- Handles custom tour inquiries

**Configuration:**
- Backend: `app/Http/Controllers/ChatbotController.php`
- Frontend: `resources/views/components/chatbot-widget.blade.php`
- Uses `gemini-2.0-flash` model for fast responses

### 2. AI Content Generator (Admin Panel)

**Location:** Tours and Blog create/edit pages (top-right button)

**Capabilities:**
- **Generate Tour Content:**
  - Excerpt (short description)
  - Full content (detailed description)
  - **Itinerary** (day-by-day activities - auto-generated based on duration)
  - Highlights (key features)
  - Included/Excluded services
  - SEO meta title, description, keywords

- **Generate Blog Content:**
  - Excerpt
  - Full article with HTML headings
  - Highlights
  - SEO meta data

**How to Use:**
1. Navigate to Tours or Blog in admin
2. Click "Create" or edit existing
3. Fill in the **title** (required)
4. For tours: Add **destination** and **duration** for better results
5. Click **"✨ Generate with AI"** button (top-right)
6. Confirm the generation
7. Review and edit the AI-generated content
8. Save or publish

**What Gets Generated:**

For a tour with title "5-Day Safari Adventure" and duration "5 days":
```json
{
    "excerpt": "Embark on an unforgettable 5-day safari adventure...",
    "content": "Full detailed description of the tour...",
    "itinerary": [
        {"title": "Day 1: Arrival and Welcome", "description": "Arrive at the safari lodge..."},
        {"title": "Day 2: Morning Game Drive", "description": "Wake up early for..."},
        ...
    ],
    "highlights": ["Professional guide", "Game drives", "Sunset views", ...],
    "included_services": ["Professional tour guide", "Transportation", ...],
    "excluded_services": ["Personal expenses", "Travel insurance", ...],
    "seo_meta_title": "5-Day Safari Adventure | Best Tour Package",
    "seo_meta_description": "Book our 5-day safari adventure tour. Includes game drives, professional guides, and unforgettable experiences. Perfect for wildlife enthusiasts.",
    "seo_keywords": "safari, 5 day tour, wildlife, adventure, game drive"
}
```

---

## 🤖 How AI Content Generation Works

### Tour Content Generation

The AI generates content based on:
- **Tour Title** - Main topic
- **Destination** - Location context (if selected)
- **Duration** - Number of days (parsed from duration field)
- **Itinerary** - Existing itinerary (if provided, otherwise generated)

**Generated Fields:**
- `excerpt` - Compelling 2-3 sentence summary
- `content` - Detailed 300-500 word description
- `itinerary` - Day-by-day activities (auto-generated if duration provided)
- `highlights` - 5 key features
- `included_services` - List of included services
- `excluded_services` - List of excluded services
- `seo_meta_title` - SEO-optimized title (50-60 chars)
- `seo_meta_description` - SEO description (150-160 chars)
- `seo_keywords` - Comma-separated keywords

### Blog Content Generation

The AI generates content based on:
- **Blog Title** - Main topic
- **Category** - Content category (if selected)

**Generated Fields:**
- `excerpt` - Compelling summary (150-200 chars)
- `content` - Full article (500-800 words) with HTML headings
- `highlights` - Key points
- `seo_meta_title` - SEO-optimized title
- `seo_meta_description` - SEO description
- `seo_keywords` - Comma-separated keywords

---

## 🔧 Technical Details

### Service Class
**File:** `app/Services/GeminiContentService.php`

**Methods:**
- `generateTourContent()` - Generate tour description
- `generateTourSeo()` - Generate tour SEO data
- `generateBlogContent()` - Generate blog article
- `generateBlogSeo()` - Generate blog SEO data
- `generateCompleteTour()` - Generate all tour fields including itinerary
- `generateCompleteBlog()` - Generate all blog fields
- `generateItinerary()` - Generate day-by-day itinerary

### Filament Pages
**Files:**
- `app/Filament/Resources/TourResource/Pages/CreateTour.php`
- `app/Filament/Resources/TourResource/Pages/EditTour.php`

These pages include the "Generate with AI" action button.

### API Endpoints
- `POST /admin/api/generate-tour-content` - Generate tour content
- `POST /admin/api/generate-tour-seo` - Generate tour SEO
- `POST /admin/api/generate-blog-content` - Generate blog content
- `POST /admin/api/generate-blog-seo` - Generate blog SEO
- `GET /admin/api/ai-available` - Check AI availability

---

## 🛠️ Troubleshooting

### "Generate with AI" button not showing?
1. Ensure you're on the create or edit page for Tours
2. Check if `GEMINI_API_KEY` is set in `.env`
3. Run `php artisan config:clear`
4. Clear browser cache

### Content generation returning fallback/generic content?
**This is likely a quota issue.** The Gemini API free tier has strict rate limits:
- **Free tier limits:** 15 requests per minute for `gemini-2.0-flash`
- When quota is exceeded, the system returns fallback content

**Solutions:**
1. **Wait 49+ seconds** between generation attempts (quota resets automatically)
2. **Switch to `gemini-1.5-flash`** which has higher free tier limits:
   - Add to `.env`: `GEMINI_MODEL=gemini-1.5-flash`
   - Run: `php artisan config:clear`
3. **Add billing** to your Google AI project for higher limits:
   - Go to [Google AI Studio](https://aistudio.google.com/)
   - Add a billing account
   - This increases your quota significantly

**Check logs for quota errors:**
```bash
# View recent errors
Get-Content storage/logs/laravel.log -Tail 50

# Look for "RESOURCE_EXHAUSTED" or "quota exceeded"
```

### Content generation failing completely?
1. Verify API key at [Google AI Studio](https://aistudio.google.com/)
2. Check API quota and billing
3. Review logs: `storage/logs/laravel.log`
4. Ensure title is filled before generating
5. For itinerary generation, include a number in the duration field (e.g., "5 days")

### Chatbot not responding?
1. Check `GEMINI_API_KEY` in `.env`
2. Run `php artisan config:clear`
3. Verify route exists: `php artisan route:list | grep chatbot`
4. Check for quota issues (same as above)

---

## 📊 API Usage & Costs

Google Gemini API offers:
- **Free tier:** 15 requests per minute (rate limited)
- **Paid tier:** Higher limits available

Check [Google AI Studio pricing](https://ai.google.dev/pricing) for current rates.

---

## 🎨 Customization

### Modify AI Personality
Edit prompts in `app/Services/GeminiContentService.php`:
- `buildTourContentPrompt()` - Tour content style
- `buildBlogContentPrompt()` - Blog content style
- `generateItinerary()` - Itinerary generation style

### Change Content Length
Modify `maxOutputTokens` in `makeRequest()` method (default: 2000)

### Adjust Temperature
Change `temperature` in `makeRequest()` (0.0 = focused, 1.0 = creative)

---

## 📝 Best Practices

1. **Always review AI-generated content** before publishing
2. **Add specific details** to itinerary for better tour descriptions
3. **Use descriptive titles** for better content generation
4. **Include duration as a number** (e.g., "5 days") for itinerary generation
5. **Edit SEO data** to match your specific keywords
6. **Monitor API usage** to avoid unexpected costs

---

## 🆘 Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify API key at Google AI Studio
3. Ensure `.env` is properly configured
4. Clear caches: `php artisan optimize:clear`

---

**You're all set!** 🎉 Your website now has powerful AI capabilities for both customer service and automated content creation with SEO optimization and itinerary generation.
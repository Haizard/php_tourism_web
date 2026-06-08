@props([
    'type' => 'tour', // 'tour' or 'blog'
    'titleField' => 'title',
    'contentField' => 'content',
    'excerptField' => 'excerpt',
    'highlightsField' => 'highlights',
    'includedField' => 'included_services',
    'excludedField' => 'excluded_services',
    'seoTitleField' => 'seo_meta_title',
    'seoDescriptionField' => 'seo_meta_description',
    'seoKeywordsField' => 'seo_keywords',
    'destinationField' => 'destination_id',
    'categoryField' => 'category_id',
    'durationField' => 'duration',
    'itineraryField' => 'itinerary',
])

<div 
    x-data="aiContentGenerator({
        type: '{{ $type }}',
        titleField: '{{ $titleField }}',
        contentField: '{{ $contentField }}',
        excerptField: '{{ $excerptField }}',
        highlightsField: '{{ $highlightsField }}',
        includedField: '{{ $includedField }}',
        excludedField: '{{ $excludedField }}',
        seoTitleField: '{{ $seoTitleField }}',
        seoDescriptionField: '{{ $seoDescriptionField }}',
        seoKeywordsField: '{{ $seoKeywordsField }}',
        destinationField: '{{ $destinationField }}',
        categoryField: '{{ $categoryField }}',
        durationField: '{{ $durationField }}',
        itineraryField: '{{ $itineraryField }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border border-purple-200"
>
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <h3 class="font-semibold text-purple-900">AI Content Generator</h3>
            <span x-show="!aiAvailable" class="text-xs text-red-500 font-normal">(API not configured)</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <button
                type="button"
                @click="generateContent()"
                :disabled="generating || !aiAvailable"
                class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2"
            >
                <span x-show="!generating">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Generate Content
                </span>
                <span x-show="generating" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generating...
                </span>
            </button>
        </div>
        <div>
            <button
                type="button"
                @click="generateSeo()"
                :disabled="generating || !aiAvailable"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2"
            >
                <span x-show="!generating">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate SEO Data
                </span>
                <span x-show="generating" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generating...
                </span>
            </button>
        </div>
    </div>

    <div x-show="message" 
         :class="messageType === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800'"
         class="p-3 rounded-lg border text-sm mb-4"
         x-text="message">
    </div>

    <div class="text-xs text-gray-500">
        <p><strong>Tip:</strong> Fill in the title and other details first, then click "Generate Content" to auto-fill the content fields with AI-generated text.</p>
    </div>
</div>

<script>
function aiContentGenerator(config) {
    return {
        type: config.type,
        generating: false,
        aiAvailable: true,
        message: '',
        messageType: 'success',
        
        init() {
            this.checkAvailability();
        },
        
        async checkAvailability() {
            try {
                const response = await fetch('/admin/api/ai-available');
                const data = await response.json();
                this.aiAvailable = data.available;
            } catch (error) {
                this.aiAvailable = false;
            }
        },
        
        async generateContent() {
            if (this.generating || !this.aiAvailable) return;
            
            this.generating = true;
            this.message = '';
            
            try {
                // Get form data
                const formData = this.getFormData();
                
                const endpoint = this.type === 'tour' 
                    ? '/admin/api/generate-tour-content' 
                    : '/admin/api/generate-blog-content';
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.applyContent(result.data);
                    this.showMessage('Content generated successfully! Review and edit as needed.', 'success');
                } else {
                    this.showMessage(result.message || 'Failed to generate content', 'error');
                }
            } catch (error) {
                this.showMessage('An error occurred: ' + error.message, 'error');
            } finally {
                this.generating = false;
            }
        },
        
        async generateSeo() {
            if (this.generating || !this.aiAvailable) return;
            
            this.generating = true;
            this.message = '';
            
            try {
                const formData = this.getFormData();
                
                const endpoint = this.type === 'tour' 
                    ? '/admin/api/generate-tour-seo' 
                    : '/admin/api/generate-blog-seo';
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.applySeo(result.data);
                    this.showMessage('SEO data generated successfully!', 'success');
                } else {
                    this.showMessage(result.message || 'Failed to generate SEO data', 'error');
                }
            } catch (error) {
                this.showMessage('An error occurred: ' + error.message, 'error');
            } finally {
                this.generating = false;
            }
        },
        
        getFormData() {
            const data = {
                title: this.getFieldValue(config.titleField) || '',
            };
            
            if (this.type === 'tour') {
                data.destination_id = this.getFieldValue(config.destinationField);
                data.duration = this.getFieldValue(config.durationField);
                data.itinerary = this.getFieldValue(config.itineraryField) || [];
                data.content = this.getFieldValue(config.contentField) || '';
            } else {
                data.category_id = this.getFieldValue(config.categoryField);
                data.content = this.getFieldValue(config.contentField) || '';
            }
            
            return data;
        },
        
        getFieldValue(field) {
            // Try different input selectors
            const selectors = [
                `[name="${field}"]`,
                `[id="${field}"]`,
                `[wire\\:model="${field}"]`,
            ];
            
            for (const selector of selectors) {
                const element = document.querySelector(selector);
                if (element) {
                    if (element.type === 'checkbox') return element.checked;
                    if (element.type === 'file') return element.files[0];
                    return element.value;
                }
            }
            
            // Try to find in Alpine data
            const alpineElement = document.querySelector(`[x-data*="${field}"]`);
            if (alpineElement && alpineElement.__x) {
                return alpineElement.__x.$data[field];
            }
            
            return null;
        },
        
        setFieldValue(field, value) {
            const selectors = [
                `[name="${field}"]`,
                `[id="${field}"]`,
                `[wire\\:model="${field}"]`,
            ];
            
            for (const selector of selectors) {
                const element = document.querySelector(selector);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = value;
                    } else if (element.type === 'file') {
                        // Skip file inputs
                    } else {
                        element.value = value;
                        // Trigger change event
                        element.dispatchEvent(new Event('change', { bubbles: true }));
                        element.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    return true;
                }
            }
            
            return false;
        },
        
        applyContent(data) {
            if (data.excerpt) {
                this.setFieldValue(config.excerptField, data.excerpt);
            }
            if (data.content) {
                this.setFieldValue(config.contentField, data.content);
                // Also update TinyMCE or other rich text editors
                if (typeof tinymce !== 'undefined') {
                    const editor = tinymce.get(config.contentField);
                    if (editor) {
                        editor.setContent(data.content);
                    }
                }
            }
            if (data.highlights && Array.isArray(data.highlights)) {
                this.setFieldValue(config.highlightsField, JSON.stringify(data.highlights));
            }
            if (this.type === 'tour') {
                if (data.included_services && Array.isArray(data.included_services)) {
                    this.setFieldValue(config.includedField, JSON.stringify(data.included_services));
                }
                if (data.excluded_services && Array.isArray(data.excluded_services)) {
                    this.setFieldValue(config.excludedField, JSON.stringify(data.excluded_services));
                }
            }
            // Apply SEO data as well
            this.applySeo(data);
        },
        
        applySeo(data) {
            if (data.seo_meta_title) {
                this.setFieldValue(config.seoTitleField, data.seo_meta_title);
            }
            if (data.seo_meta_description) {
                this.setFieldValue(config.seoDescriptionField, data.seo_meta_description);
            }
            if (data.seo_keywords) {
                this.setFieldValue(config.seoKeywordsField, data.seo_keywords);
            }
        },
        
        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                this.message = '';
            }, 5000);
        }
    };
}
</script>
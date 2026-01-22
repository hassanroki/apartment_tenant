<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Translate Language Toggle</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #eaeaea;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        #language {
            display: flex;
            background-color: #fff;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            border: 2px solid #3498db;
            user-select: none;
        }

        #language p {
            padding: 12px 24px;
            margin: 0;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            min-width: 100px;
        }

        #language p:first-child {
            color: #3498db;
            background-color: #fff;
        }

        #language p:last-child {
            color: #fff;
            background-color: #3498db;
        }

        #language.bangla p:first-child {
            color: #fff;
            background-color: #3498db;
        }

        #language.bangla p:last-child {
            color: #3498db;
            background-color: #fff;
        }

        .content {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 32px;
        }

        h2 {
            color: #2c3e50;
            margin: 25px 0 15px;
        }

        p {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            color: #7f8c8d;
            font-size: 14px;
        }

        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <div class="container">
        <header>
            <div class="logo">MyWebsite</div>
            <h2 id="language">
                <p>বাংলা</p>
                <p>English</p>
            </h2>
        </header>

        <main>
            <section class="content">
                <h1>Welcome to Our Website</h1>
                <p>This is a demonstration of Google Translate like functionality. Your entire website is in English by default.</p>
                <p>When you click on "বাংলা", the entire page content will be translated to Bangla instantly using Google Translate API.</p>

                <h2>Our Services</h2>
                <p>We provide excellent web development services with focus on user experience and modern design principles. Our team of experienced developers can create responsive, fast, and secure websites that meet your business needs.</p>

                <h2>About Us</h2>
                <p>Founded in 2010, we have been helping businesses establish their online presence with cutting-edge web solutions. Our mission is to deliver high-quality digital products that drive growth and success for our clients.</p>

                <h2>Contact Us</h2>
                <p>Feel free to reach out to us for any inquiries or project discussions. We're here to help you achieve your digital goals. You can email us at info@mywebsite.com or call us at +880 1234 567890.</p>

                <button class="btn" id="ctaButton">Get Started Today</button>
            </section>
        </main>

        <footer class="footer">
            <p>© 2023 MyWebsite. All rights reserved. | Privacy Policy | Terms of Service</p>
        </footer>
    </div>

    <script>
        // Google Translate API Key (এটি একটি উদাহরণ কী, আপনার নিজের API কী ব্যবহার করুন)
        // এখানে রেখে দেওয়া হয়েছে দেখানোর জন্য, বাস্তবে এটা .env ফাইলে রাখবেন
        const GOOGLE_TRANSLATE_API_KEY = 'YOUR_API_KEY_HERE'; // আপনার API Key এখানে বসাবেন

        // Google Translate API endpoint
        const TRANSLATE_API_URL = `https://translation.googleapis.com/language/translate/v2`;

        // DOM elements
        const languageToggle = document.getElementById('language');
        const [banglaBtn, englishBtn] = languageToggle.querySelectorAll('p');
        const loadingElement = document.getElementById('loading');

        // বর্তমান ভাষা ট্র্যাক রাখা
        let currentLang = 'en';
        let originalTexts = new Map();

        // সকল translatable elements সংগ্রহ করা
        function collectTranslatableElements() {
            const elements = [];

            // শুধুমাত্র টেক্সট নোড সমৃদ্ধ elements নির্বাচন
            const selectors = [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'p', 'span', 'a', 'button', 'li', 'td', 'th',
                'div:not([class*="icon"]):not([class*="btn"])'
            ];

            // মূল কন্টেন্ট এলিমেন্টের ভিতরের সব টেক্সট সংগ্রহ
            const contentElement = document.querySelector('.content');
            const allElements = contentElement ?
                contentElement.querySelectorAll('*') :
                document.body.querySelectorAll('*');

            allElements.forEach(element => {
                // শুধুমাত্র যেসব elements-এ টেক্সট আছে এবং তারা visible
                if (element.childNodes.length === 1 &&
                    element.childNodes[0].nodeType === Node.TEXT_NODE &&
                    element.textContent.trim().length > 0 &&
                    !element.classList.contains('not-translate') &&
                    window.getComputedStyle(element).display !== 'none') {

                    elements.push(element);

                    // মূল টেক্সট সংরক্ষণ
                    if (!originalTexts.has(element)) {
                        originalTexts.set(element, element.textContent);
                    }
                }
            });

            return elements;
        }

        // টেক্সট ট্রান্সলেট করা
        async function translateText(text, targetLang) {
            try {
                // যদি API Key না থাকে, তাহলে demo mode-এ কাজ করবে
                if (!GOOGLE_TRANSLATE_API_KEY || GOOGLE_TRANSLATE_API_KEY === 'YOUR_API_KEY_HERE') {
                    return demoTranslate(text, targetLang);
                }

                const response = await fetch(TRANSLATE_API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        q: text,
                        target: targetLang,
                        key: GOOGLE_TRANSLATE_API_KEY
                    })
                });

                const data = await response.json();
                return data.data.translations[0].translatedText;

            } catch (error) {
                console.error('Translation error:', error);
                return demoTranslate(text, targetLang);
            }
        }

        // ডেমো ট্রান্সলেশন (যদি API Key না থাকে)
        function demoTranslate(text, targetLang) {
            // কিছু সাধারণ টেক্সটের বাংলা ভার্সন
            const demoTranslations = {
                'Welcome to Our Website': 'আমাদের ওয়েবসাইটে স্বাগতম',
                'This is a demonstration of Google Translate like functionality. Your entire website is in English by default.':
                    'এটি Google Translate এর মত কার্যকারিতার একটি প্রদর্শন। আপনার পুরো ওয়েবসাইট ডিফল্টভাবে ইংরেজিতে রয়েছে।',
                'When you click on "বাংলা", the entire page content will be translated to Bangla instantly using Google Translate API.':
                    'আপনি "বাংলা" ক্লিক করলে, পুরো পৃষ্ঠার বিষয়বস্তু Google Translate API ব্যবহার করে তাত্ক্ষণিকভাবে বাংলায় অনুবাদ করা হবে।',
                'Our Services': 'আমাদের সেবাসমূহ',
                'We provide excellent web development services with focus on user experience and modern design principles. Our team of experienced developers can create responsive, fast, and secure websites that meet your business needs.':
                    'আমরা ব্যবহারকারীর অভিজ্ঞতা এবং আধুনিক ডিজাইন নীতির উপর ফোকাস সহ চমৎকার ওয়েব ডেভেলপমেন্ট সেবা প্রদান করি। আমাদের অভিজ্ঞ ডেভেলপারদের দল প্রতিক্রিয়াশীল, দ্রুত এবং নিরাপদ ওয়েবসাইট তৈরি করতে পারে যা আপনার ব্যবসায়িক চাহিদা পূরণ করে।',
                'About Us': 'আমাদের সম্পর্কে',
                'Founded in 2010, we have been helping businesses establish their online presence with cutting-edge web solutions. Our mission is to deliver high-quality digital products that drive growth and success for our clients.':
                    '২০১০ সালে প্রতিষ্ঠিত, আমরা ব্যবসায়ীদের তাদের অনলাইন উপস্থিতি প্রতিষ্ঠা করতে সাহায্য করছি অত্যাধুনিক ওয়েব সমাধান দিয়ে। আমাদের মিশন হল উচ্চ-গুণগত ডিজিটাল পণ্য সরবরাহ করা যা আমাদের ক্লায়েন্টদের জন্য বৃদ্ধি এবং সাফল্য চালিত করে।',
                'Contact Us': 'যোগাযোগ করুন',
                'Feel free to reach out to us for any inquiries or project discussions. We\'re here to help you achieve your digital goals. You can email us at info@mywebsite.com or call us at +880 1234 567890.':
                    'যেকোনো জিজ্ঞাসা বা প্রকল্পের আলোচনার জন্য আমাদের সাথে যোগাযোগ করতে নির্দ্বিধায়। আপনার ডিজিটাল লক্ষ্য অর্জনে আমরা আপনাকে সাহায্য করতে এখানে আছি। আপনি আমাদের info@mywebsite.com এ ইমেল করতে পারেন অথবা +880 ১২৩৪ ৫৬৭৮৯০ নম্বরে কল করতে পারেন।',
                'Get Started Today': 'আজই শুরু করুন',
                '© 2023 MyWebsite. All rights reserved. | Privacy Policy | Terms of Service':
                    '© ২০২৩ আমার ওয়েবসাইট। সর্বস্বত্ব সংরক্ষিত। | গোপনীয়তা নীতি | সেবার শর্তাবলী'
            };

            // যদি টেক্সটটি ডেমো ট্রান্সলেশনে থাকে
            if (demoTranslations[text]) {
                return demoTranslations[text];
            }

            // না থাকলে একই টেক্সট ফেরত দেবে
            return text;
        }

        // পুরো পেজ ট্রান্সলেট করা
        async function translatePage(targetLang) {
            // লোডিং দেখান
            loadingElement.style.display = 'flex';

            const elements = collectTranslatableElements();

            // প্রতিটি element ট্রান্সলেট করা
            for (const element of elements) {
                const text = originalTexts.get(element) || element.textContent;

                if (text && text.trim().length > 0) {
                    try {
                        const translatedText = await translateText(text, targetLang);
                        element.textContent = translatedText;
                    } catch (error) {
                        console.warn('Failed to translate element:', element, error);
                    }
                }
            }

            // ভাষা স্টাইল আপডেট
            if (targetLang === 'bn') {
                languageToggle.classList.add('bangla');
            } else {
                languageToggle.classList.remove('bangla');
            }

            currentLang = targetLang;

            // লোডিং লুকান
            setTimeout(() => {
                loadingElement.style.display = 'none';
            }, 500);
        }

        // বাংলা বাটনে ক্লিক করলে
        banglaBtn.addEventListener('click', async function() {
            if (currentLang !== 'bn') {
                await translatePage('bn');
            }
        });

        // ইংরেজি বাটনে ক্লিক করলে
        englishBtn.addEventListener('click', function() {
            if (currentLang !== 'en') {
                // মূল ইংরেজি টেক্সট ফিরিয়ে আনা
                originalTexts.forEach((text, element) => {
                    element.textContent = text;
                });

                languageToggle.classList.remove('bangla');
                currentLang = 'en';
            }
        });

        // API Key চেক
        function checkApiKey() {
            if (!GOOGLE_TRANSLATE_API_KEY || GOOGLE_TRANSLATE_API_KEY === 'YOUR_API_KEY_HERE') {
                console.warn('⚠️ Google Translate API Key is not set. Using demo translation mode.');

                // ইউজারকে জানানো (ঐচ্ছিক)
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #ff9800;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    z-index: 1001;
                    font-size: 14px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                `;
                notification.textContent = '⚠️ Using demo translation. Add your Google API Key for real translation.';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
        }

        // পেজ লোড হওয়ার পরে
        document.addEventListener('DOMContentLoaded', function() {
            checkApiKey();

            // সকল elements-এর মূল টেক্সট সংরক্ষণ
            collectTranslatableElements();

            // Optional: Add hover effects
            [banglaBtn, englishBtn].forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });

                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>

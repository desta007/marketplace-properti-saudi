// SaudiProp - Main Application JavaScript
function app() {
    return {
        currentLang: localStorage.getItem('lang') || 'en',
        mobileMenu: false,
        langFlags: {
            en: '🇬🇧',
            ar: '🇸🇦',
            id: '🇮🇩'
        },

        // Sample cities data with multi-language names
        cities: [
            {
                name: 'Riyadh',
                name_ar: 'الرياض',
                name_id: 'Riyadh',
                count: 3245,
                image: 'https://images.unsplash.com/photo-1586724237569-f3d0c1dee8c6?w=400&h=300&fit=crop'
            },
            {
                name: 'Jeddah',
                name_ar: 'جدة',
                name_id: 'Jeddah',
                count: 2180,
                image: 'https://images.unsplash.com/photo-1578895101408-1a36b834405b?w=400&h=300&fit=crop'
            },
            {
                name: 'Dammam',
                name_ar: 'الدمام',
                name_id: 'Dammam',
                count: 1456,
                image: 'https://images.unsplash.com/photo-1518684079-3c830dcef090?w=400&h=300&fit=crop'
            },
            {
                name: 'Makkah',
                name_ar: 'مكة المكرمة',
                name_id: 'Mekkah',
                count: 890,
                image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=400&h=300&fit=crop'
            },
            {
                name: 'Madinah',
                name_ar: 'المدينة المنورة',
                name_id: 'Madinah',
                count: 654,
                image: 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=400&h=300&fit=crop'
            }
        ],

        // Get city name based on current language
        getCityName(city) {
            if (this.currentLang === 'ar') return city.name_ar;
            if (this.currentLang === 'id') return city.name_id;
            return city.name;
        },


        // Sample properties data (Real Saudi property examples)
        properties: [
            {
                id: 1,
                title: 'Modern Villa in Al-Nakheel District',
                title_ar: 'فيلا حديثة في حي النخيل',
                title_id: 'Vila Modern di Distrik Al-Nakheel',
                location: 'Al-Nakheel, Riyadh',
                price: 1200000,
                purpose: 'sale',
                beds: 5,
                baths: 4,
                area: 450,
                featured: true,
                image: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=400&fit=crop'
            },
            {
                id: 2,
                title: 'Luxury Apartment in Corniche',
                title_ar: 'شقة فاخرة على الكورنيش',
                title_id: 'Apartemen Mewah di Corniche',
                location: 'Corniche, Jeddah',
                price: 85000,
                purpose: 'rent',
                beds: 3,
                baths: 2,
                area: 180,
                featured: true,
                image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop'
            },
            {
                id: 3,
                title: 'Compound Villa in Al-Hamra',
                title_ar: 'فيلا كمباوند في الحمراء',
                title_id: 'Vila Compound di Al-Hamra',
                location: 'Al-Hamra, Riyadh',
                price: 150000,
                purpose: 'rent',
                beds: 4,
                baths: 3,
                area: 320,
                featured: false,
                image: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop'
            },
            {
                id: 4,
                title: 'Commercial Land in Al-Khobar',
                title_ar: 'أرض تجارية في الخبر',
                title_id: 'Tanah Komersial di Al-Khobar',
                location: 'Al-Khobar, Eastern',
                price: 5500000,
                purpose: 'sale',
                beds: 0,
                baths: 0,
                area: 2500,
                featured: false,
                image: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600&h=400&fit=crop'
            }
        ],

        // Translation function
        t(key) {
            const keys = key.split('.');
            let value = translations[this.currentLang];
            for (const k of keys) {
                value = value?.[k];
            }
            return value || key;
        },

        // Set language
        setLang(lang) {
            this.currentLang = lang;
            localStorage.setItem('lang', lang);
            document.documentElement.lang = lang;
            document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
        },

        // Initialize
        init() {
            const savedLang = localStorage.getItem('lang');
            if (savedLang) {
                this.currentLang = savedLang;
                document.documentElement.lang = savedLang;
                document.documentElement.dir = savedLang === 'ar' ? 'rtl' : 'ltr';
            }
        }
    };
}

// Property detail page data
function propertyDetail() {
    return {
        ...app(),
        property: {
            id: 1,
            title_en: 'Luxury Villa with Pool in Al-Nakheel District',
            title_ar: 'فيلا فاخرة مع مسبح في حي النخيل',
            title_id: 'Vila Mewah dengan Kolam Renang di Distrik Al-Nakheel',
            description_en: 'Stunning modern villa located in the prestigious Al-Nakheel district of Riyadh. This exceptional property features high-end finishes throughout, including marble flooring, custom Italian kitchen, and smart home technology. The villa offers spacious living areas, a beautiful private garden, and a temperature-controlled swimming pool.',
            description_ar: 'فيلا حديثة مذهلة تقع في حي النخيل الراقي بالرياض. يتميز هذا العقار الاستثنائي بتشطيبات عالية الجودة في جميع أنحائه، بما في ذلك الأرضيات الرخامية والمطبخ الإيطالي المخصص وتقنية المنزل الذكي.',
            description_id: 'Vila modern yang menakjubkan terletak di distrik Al-Nakheel yang prestisius di Riyadh. Properti ini menampilkan finishing berkualitas tinggi termasuk lantai marmer, dapur Italia kustom, dan teknologi rumah pintar.',
            location: 'Al-Nakheel District, Riyadh',
            price: 1200000,
            purpose: 'sale',
            beds: 5,
            baths: 4,
            area: 450,
            parking: 2,
            year_built: 2022,
            rega_license: 'REGA-2024-12345',
            lat: 24.7136,
            lng: 46.6753,
            qibla: 245,
            images: [
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800',
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800'
            ],
            features: {
                majlis: true,
                maidRoom: true,
                driverRoom: true,
                separateEntrance: true,
                pool: true,
                garden: true,
                parking: true,
                elevator: true,
                ac: true,
                kitchen: true
            },
            agent: {
                name: 'Ahmed Al-Rashid',
                company: 'Saudi Elite Properties',
                phone: '+966501234567',
                license: 'FAL-2024-67890',
                verified: true,
                image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop'
            }
        },
        currentImage: 0,

        get title() {
            if (this.currentLang === 'ar') return this.property.title_ar;
            if (this.currentLang === 'id') return this.property.title_id;
            return this.property.title_en;
        },

        get description() {
            if (this.currentLang === 'ar') return this.property.description_ar;
            if (this.currentLang === 'id') return this.property.description_id;
            return this.property.description_en;
        },

        nextImage() {
            this.currentImage = (this.currentImage + 1) % this.property.images.length;
        },

        prevImage() {
            this.currentImage = (this.currentImage - 1 + this.property.images.length) % this.property.images.length;
        },

        initMap() {
            const map = L.map('property-map').setView([this.property.lat, this.property.lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            L.marker([this.property.lat, this.property.lng]).addTo(map)
                .bindPopup(this.title)
                .openPopup();
        }
    };
}

// Properties listing page
function propertiesPage() {
    return {
        ...app(),
        showFilters: true,
        viewMode: 'grid',
        sortBy: 'newest',
        filters: {
            city: '',
            type: '',
            purpose: '',
            minPrice: '',
            maxPrice: '',
            beds: '',
            baths: ''
        },

        allProperties: [
            // Real Saudi property data examples
            {
                id: 1,
                title: 'Modern Villa in Al-Nakheel',
                location: 'Al-Nakheel, Riyadh',
                price: 1200000,
                purpose: 'sale',
                type: 'villa',
                beds: 5,
                baths: 4,
                area: 450,
                featured: true,
                image: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=400&fit=crop'
            },
            {
                id: 2,
                title: 'Corniche Luxury Apartment',
                location: 'Corniche, Jeddah',
                price: 85000,
                purpose: 'rent',
                type: 'apartment',
                beds: 3,
                baths: 2,
                area: 180,
                featured: true,
                image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop'
            },
            {
                id: 3,
                title: 'Al-Hamra Compound Villa',
                location: 'Al-Hamra, Riyadh',
                price: 150000,
                purpose: 'rent',
                type: 'compound',
                beds: 4,
                baths: 3,
                area: 320,
                featured: false,
                image: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600&h=400&fit=crop'
            },
            {
                id: 4,
                title: 'Commercial Land Al-Khobar',
                location: 'Al-Khobar, Eastern',
                price: 5500000,
                purpose: 'sale',
                type: 'land',
                beds: 0,
                baths: 0,
                area: 2500,
                featured: false,
                image: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600&h=400&fit=crop'
            },
            {
                id: 5,
                title: 'Diplomatic Quarter Villa',
                location: 'DQ, Riyadh',
                price: 2500000,
                purpose: 'sale',
                type: 'villa',
                beds: 6,
                baths: 5,
                area: 600,
                featured: true,
                image: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&h=400&fit=crop'
            },
            {
                id: 6,
                title: 'King Abdullah Economic City Apt',
                location: 'KAEC, Jeddah',
                price: 65000,
                purpose: 'rent',
                type: 'apartment',
                beds: 2,
                baths: 2,
                area: 120,
                featured: false,
                image: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600&h=400&fit=crop'
            }
        ],

        get filteredProperties() {
            return this.allProperties.filter(p => {
                if (this.filters.purpose && p.purpose !== this.filters.purpose) return false;
                if (this.filters.type && p.type !== this.filters.type) return false;
                if (this.filters.beds && p.beds < parseInt(this.filters.beds)) return false;
                if (this.filters.minPrice && p.price < parseInt(this.filters.minPrice)) return false;
                if (this.filters.maxPrice && p.price > parseInt(this.filters.maxPrice)) return false;
                return true;
            }).sort((a, b) => {
                if (this.sortBy === 'priceHigh') return b.price - a.price;
                if (this.sortBy === 'priceLow') return a.price - b.price;
                return b.id - a.id; // newest
            });
        },

        resetFilters() {
            this.filters = { city: '', type: '', purpose: '', minPrice: '', maxPrice: '', beds: '', baths: '' };
        }
    };
}

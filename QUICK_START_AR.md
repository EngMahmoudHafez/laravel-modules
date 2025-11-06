# دليل سريع: Laravel Modules Package

## 🎯 ما هي هذه الحزمة؟

حزمة Laravel Modules تسمح لك بتقسيم تطبيق Laravel الكبير إلى **وحدات منفصلة**، كل وحدة تحتوي على:
- Controllers, Models, Views
- Routes, Migrations
- Service Providers
- وكل شيء آخر

---

## 🔄 كيف تعمل؟ (باختصار)

### 1. التثبيت والتسجيل
```
Composer Install
    ↓
Laravel Auto-Discovery
    ↓
LaravelModulesServiceProvider::register()
    ↓
تسجيل Repository + Activator
```

### 2. اكتشاف Modules
```
FileRepository::scan()
    ↓
البحث عن module.json في مجلد Modules/
    ↓
إنشاء كائن Module لكل module.json
    ↓
حفظ في Cache (ModuleManifest)
```

### 3. تسجيل وتشغيل
```
FileRepository::register()
    ↓
ترتيب Modules حسب Priority
    ↓
Module::register() لكل Module
    ↓
Module::boot() لكل Module
```

---

## 📂 الملفات الرئيسية

### 1. Service Provider
- `src/LaravelModulesServiceProvider.php` - نقطة الدخول الرئيسية

### 2. Repository
- `src/FileRepository.php` - الكلاس الأساسي
- `src/Laravel/LaravelFileRepository.php` - التطبيق الخاص بـ Laravel

### 3. Module Class
- `src/Module.php` - الكلاس الأساسي للـ Module
- `src/Laravel/Module.php` - التطبيق الخاص بـ Laravel

### 4. Helpers
- `src/helpers.php` - دوال مساعدة (module(), module_path())

### 5. Commands
- `src/Commands/` - جميع أوامر Artisan

---

## 🛠️ الاستخدام الأساسي

### إنشاء Module
```bash
php artisan module:make Blog
```

### في الكود
```php
// باستخدام Facade
use Nwidart\Modules\Facades\Module;

Module::find('Blog');
Module::enable('Blog');
Module::isEnabled('Blog');

// باستخدام Helper
if (module('Blog')) {
    // Module مفعل
}

$blog = module('Blog', true); // جلب Instance
```

### في Blade
```blade
@module('Blog')
    <p>Blog is enabled</p>
@endmodule
```

---

## 🔍 كيف تفهم أي حزمة Laravel؟

### الخطوات:

1. **ابحث عن Service Provider**
   - في `composer.json` → `extra.laravel.providers`
   - أو في `config/app.php`

2. **افهم `register()` و `boot()`**
   - `register()`: تسجيل Services في Container
   - `boot()`: استخدام Services بعد التسجيل

3. **استكشف البنية**
   - اقرأ `README.md`
   - استكشف `src/` folder
   - فهم الـ Classes والـ Interfaces

4. **جرب في Code**
   ```bash
   php artisan tinker
   >>> app('modules')
   >>> Module::all()
   ```

---

## 📊 المخطط التدفقي

```
┌─────────────────────────────────────┐
│   Laravel Application Starts        │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   Auto-Discover Packages            │
│   (from composer.json)              │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   LaravelModulesServiceProvider     │
│   ::register()                      │
│   - Register Repository             │
│   - Register Activator              │
│   - Merge Config                    │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   LaravelModulesServiceProvider     │
│   ::boot()                          │
│   - Register Namespaces             │
│   - Register Blade Directives       │
│   - Auto-discover Migrations        │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   ModuleManifest                    │
│   - Scan Modules/ folder            │
│   - Find all module.json files      │
│   - Create Module instances         │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   FileRepository::register()        │
│   - Get enabled modules             │
│   - Order by priority               │
│   - Call Module::register()         │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   FileRepository::boot()            │
│   - Get enabled modules             │
│   - Order by priority               │
│   - Call Module::boot()             │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│   Application Ready                 │
└─────────────────────────────────────┘
```

---

## 🎓 مفاهيم مهمة

### 1. Service Container
```php
// تسجيل Service
$this->app->singleton(Interface::class, Implementation::class);

// استخدام Service
$instance = app(Interface::class);
```

### 2. Auto-Discovery
Laravel يكتشف الحزم تلقائياً من `composer.json`:
```json
{
    "extra": {
        "laravel": {
            "providers": ["..."],
            "aliases": {...}
        }
    }
}
```

### 3. Service Provider Lifecycle
```php
register() → boot()
   ↓          ↓
تسجيل      استخدام
```

### 4. Facades
```php
// في composer.json
"aliases": {
    "Module": "Nwidart\\Modules\\Facades\\Module"
}

// في الكود
Module::all(); // يعمل مثل app('modules')->all()
```

---

## 📝 أوامر مفيدة

```bash
# إنشاء Module
php artisan module:make Blog

# قائمة Modules
php artisan module:list

# تفعيل/تعطيل
php artisan module:enable Blog
php artisan module:disable Blog

# إنشاء Controller
php artisan module:make-controller PostController Blog

# إنشاء Model
php artisan module:make-model Post Blog

# إنشاء Migration
php artisan module:make-migration create_posts_table Blog
```

---

## 🚀 نصائح سريعة

1. **اقرأ الكود:** ابدأ من `LaravelModulesServiceProvider`
2. **استخدم Debugging:** `dd(Module::all())`
3. **استكشف:** استخدم `php artisan tinker`
4. **افهم الـ Flow:** اتبع دورة الحياة من `register()` إلى `boot()`

---

**ملاحظة:** راجع `HOW_IT_WORKS_AR.md` للشرح التفصيلي الكامل.




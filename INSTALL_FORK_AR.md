# كيفية تثبيت الحزمة المعدلة (Fork) بدلاً من الأصلية

## 📋 الطرق المتاحة

هناك عدة طرق لتثبيت الحزمة المعدلة، اختر الطريقة المناسبة لك:

---

## 🎯 الطريقة 1: استخدام Path Repository (للتطوير المحلي)

**الأفضل للتطوير والاختبار المحلي**

### الخطوات:

1. **انسخ الحزمة إلى مجلد في مشروعك أو خارج المشروع:**

```bash
# مثال: نسخ الحزمة إلى مجلد packages بجانب مشروعك
cd ..
git clone https://github.com/YOUR_USERNAME/laravel-modules.git
# أو إذا كانت موجودة محلياً
```

2. **عدل `composer.json` في مشروع Laravel:**

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-modules"
        }
    ],
    "require": {
        "nwidart/laravel-modules": "@dev"
    }
}
```

3. **ثبت الحزمة:**

```bash
composer require nwidart/laravel-modules:@dev
```

**ملاحظات:**
- `../laravel-modules` هو المسار النسبي للحزمة
- يمكن استخدام مسار مطلق: `"/path/to/laravel-modules"`
- التعديلات على الحزمة تظهر مباشرة بدون `composer update`

---

## 🎯 الطريقة 2: استخدام VCS Repository (من GitHub)

**الأفضل إذا رفعت Fork على GitHub**

### الخطوات:

1. **ارفع Fork على GitHub:**
   - اذهب إلى GitHub
   - اعمل Fork من `nwidart/laravel-modules`
   - ارفع التعديلات الخاصة بك

2. **عدل `composer.json` في مشروع Laravel:**

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-modules.git"
        }
    ],
    "require": {
        "nwidart/laravel-modules": "dev-main"
    }
}
```

**أو إذا كان لديك branch محدد:**

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-modules.git"
        }
    ],
    "require": {
        "nwidart/laravel-modules": "dev-your-branch-name"
    }
}
```

3. **ثبت الحزمة:**

```bash
composer require nwidart/laravel-modules:dev-main
# أو
composer require nwidart/laravel-modules:dev-your-branch-name
```

**ملاحظات:**
- استبدل `YOUR_USERNAME` باسم المستخدم على GitHub
- استبدل `main` أو `your-branch-name` باسم الـ branch الذي يحتوي على التعديلات
- يجب أن يكون الـ repository عام (public) أو أن تضيف SSH key

---

## 🎯 الطريقة 3: استخدام Git Repository مباشرة

**مشابهة للطريقة 2 لكن مع إمكانية تحديد commit محدد**

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-modules.git"
        }
    ],
    "require": {
        "nwidart/laravel-modules": "dev-main#commit-hash"
    }
}
```

---

## 🎯 الطريقة 4: استخدام Package Repository (للمشاريع المتعددة)

**إذا كنت تريد استخدام الحزمة في عدة مشاريع**

### الخطوات:

1. **أنشئ `satis.json` أو استخدم خدمة مثل Packagist Private:**

```json
{
    "name": "your-private-repo",
    "homepage": "https://packages.yourdomain.com",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-modules.git"
        }
    ],
    "require-all": true
}
```

2. **في `composer.json` للمشروع:**

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://packages.yourdomain.com"
        }
    ],
    "require": {
        "nwidart/laravel-modules": "*"
    }
}
```

---

## 📝 مثال كامل لـ composer.json

### للطريقة 1 (Path Repository):

```json
{
    "name": "your-project/name",
    "type": "project",
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-modules",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "php": "^8.2",
        "nwidart/laravel-modules": "@dev"
    }
}
```

### للطريقة 2 (VCS Repository):

```json
{
    "name": "your-project/name",
    "type": "project",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-modules.git"
        }
    ],
    "require": {
        "php": "^8.2",
        "nwidart/laravel-modules": "dev-main"
    }
}
```

---

## 🔧 خطوات التثبيت الكاملة

### للطريقة 1 (Path):

```bash
# 1. تأكد من وجود الحزمة في المسار المحدد
ls ../laravel-modules

# 2. أضف repository في composer.json (كما هو موضح أعلاه)

# 3. ثبت الحزمة
composer require nwidart/laravel-modules:@dev

# 4. إذا كانت الحزمة موجودة مسبقاً، قم بالتحديث
composer update nwidart/laravel-modules
```

### للطريقة 2 (VCS):

```bash
# 1. تأكد من رفع Fork على GitHub

# 2. أضف repository في composer.json (كما هو موضح أعلاه)

# 3. ثبت الحزمة
composer require nwidart/laravel-modules:dev-main

# 4. إذا كانت الحزمة موجودة مسبقاً
composer update nwidart/laravel-modules
```

---

## ⚠️ ملاحظات مهمة

### 1. إزالة الحزمة الأصلية أولاً (إذا كانت مثبتة):

```bash
composer remove nwidart/laravel-modules
```

### 2. تنظيف Cache:

```bash
composer clear-cache
```

### 3. للطريقة 1 (Path Repository):

- استخدم `"symlink": true` لإنشاء symbolic link (أسرع)
- أو اتركه `false` لنسخ الملفات

### 4. للطريقة 2 (VCS Repository):

- تأكد من أن الـ repository عام أو أنك أضفت authentication
- استخدم `dev-main` أو `dev-branch-name` للـ branches
- استخدم `@dev` كـ minimum stability إذا لزم الأمر

### 5. تحديث الحزمة:

```bash
# للطريقة 1: التعديلات تظهر مباشرة (إذا استخدمت symlink)
# للطريقة 2: قم بـ pull التعديلات ثم:
composer update nwidart/laravel-modules
```

---

## 🎯 التوصية

- **للتطوير المحلي:** استخدم **الطريقة 1 (Path Repository)** مع `symlink: true`
- **للمشاريع المتعددة:** استخدم **الطريقة 2 (VCS Repository)**
- **للإنتاج:** استخدم **الطريقة 2** مع tag محدد

---

## 📚 مراجع

- [Composer Repositories Documentation](https://getcomposer.org/doc/05-repositories.md)
- [Path Repository](https://getcomposer.org/doc/05-repositories.md#path)
- [VCS Repository](https://getcomposer.org/doc/05-repositories.md#vcs)

---

## 🔍 التحقق من التثبيت

بعد التثبيت، تحقق من:

```bash
# 1. تحقق من الحزمة المثبتة
composer show nwidart/laravel-modules

# 2. تحقق من المسار
composer show -p nwidart/laravel-modules

# 3. تحقق من الأوامر
php artisan list | grep module
```

---

تم! الآن يمكنك استخدام الحزمة المعدلة في مشروعك! 🎉


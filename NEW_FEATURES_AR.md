# الميزات الجديدة: Repository و Service في Module Creation

## 📋 نظرة عامة

تم إضافة ميزات جديدة تسمح بإنشاء **Repository** و **RepositoryInterface** و **Service** تلقائياً عند إنشاء Module جديد.

---

## 🚀 الاستخدام

### 1. إنشاء Module مع Repository و RepositoryInterface

```bash
php artisan module:make Blog --with-repository
```

**ما سيتم إنشاؤه:**
- `Modules/Blog/app/Interfaces/BlogRepositoryInterface.php`
- `Modules/Blog/app/Repositories/BlogRepository.php`

**ملاحظة:** الـ Repository سيقوم تلقائياً بتطبيق (implements) الـ RepositoryInterface.

### 2. إنشاء Module مع Service

```bash
php artisan module:make Blog --with-service
```

**ما سيتم إنشاؤه:**
- `Modules/Blog/app/Services/BlogService.php`

### 3. إنشاء Module مع Repository و Service معاً

```bash
php artisan module:make Blog --with-repository --with-service
```

**ما سيتم إنشاؤه:**
- `Modules/Blog/app/Interfaces/BlogRepositoryInterface.php`
- `Modules/Blog/app/Repositories/BlogRepository.php`
- `Modules/Blog/app/Services/BlogService.php`

---

## 📁 البنية المولدة

### RepositoryInterface

```php
<?php

namespace Modules\Blog\Interfaces;

interface BlogRepositoryInterface
{
    /**
     * Get all records.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get a record by ID.
     *
     * @param  int  $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create a new record.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a record.
     *
     * @param  int  $id
     * @param  array  $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a record.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id);
}
```

### Repository

```php
<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Interfaces\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    /**
     * Get all records.
     *
     * @return mixed
     */
    public function all()
    {
        // Implementation
    }

    /**
     * Get a record by ID.
     *
     * @param  int  $id
     * @return mixed
     */
    public function find($id)
    {
        // Implementation
    }

    /**
     * Create a new record.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data)
    {
        // Implementation
    }

    /**
     * Update a record.
     *
     * @param  int  $id
     * @param  array  $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        // Implementation
    }

    /**
     * Delete a record.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        // Implementation
    }
}
```

### Service

```php
<?php

namespace Modules\Blog\Services;

class BlogService
{
    public function handle() {}
}
```

---

## 🔧 التعديلات التي تمت

### 1. ModuleMakeCommand
- إضافة option `--with-repository`
- إضافة option `--with-service`

### 2. ModuleGenerator
- إضافة properties `$withRepository` و `$withService`
- إضافة methods `setWithRepository()` و `setWithService()`
- تعديل `generateResources()` لإنشاء Repository و Service عند الحاجة

### 3. RepositoryMakeCommand
- تحسين `getTemplateContents()` للتحقق من وجود RepositoryInterface
- إضافة دعم لاستخدام RepositoryInterface تلقائياً في Repository

### 4. InterfaceMakeCommand
- تعديل `getStubName()` لاستخدام `repository-interface.stub` عند إنشاء RepositoryInterface

### 5. Stubs الجديدة
- `repository-interface.stub`: stub محسّن للـ RepositoryInterface
- تحسين `repository.stub`: يدعم استخدام RepositoryInterface تلقائياً

---

## 📝 أمثلة الاستخدام

### مثال 1: إنشاء Module مع Repository

```bash
php artisan module:make Product --with-repository
```

**النتيجة:**
- `Modules/Product/app/Interfaces/ProductRepositoryInterface.php`
- `Modules/Product/app/Repositories/ProductRepository.php` (يستخدم ProductRepositoryInterface)

### مثال 2: إنشاء Module مع Service

```bash
php artisan module:make Order --with-service
```

**النتيجة:**
- `Modules/Order/app/Services/OrderService.php`

### مثال 3: إنشاء Module كامل مع جميع الميزات

```bash
php artisan module:make Blog --with-repository --with-service
```

**النتيجة:**
- Module كامل مع Controller, Routes, Views
- Repository + RepositoryInterface
- Service

---

## 🎯 الفوائد

1. **توفير الوقت:** لا حاجة لإنشاء Repository و Service يدوياً
2. **البنية الموحدة:** جميع الـ Modules تتبع نفس البنية
3. **Best Practices:** استخدام Repository Pattern و Service Layer
4. **سهولة الاستخدام:** خيارات بسيطة في الأمر

---

## 🔍 التفاصيل التقنية

### كيف يعمل؟

1. عند استخدام `--with-repository`:
   - يتم إنشاء `{Module}RepositoryInterface` أولاً
   - ثم يتم إنشاء `{Module}Repository` الذي يطبق الـ Interface

2. عند استخدام `--with-service`:
   - يتم إنشاء `{Module}Service` مباشرة

3. الـ Repository يتحقق تلقائياً من وجود RepositoryInterface:
   - إذا وُجد: يستخدمه في `implements`
   - إذا لم يُوجد: لا يستخدم interface

---

## 📚 ملاحظات

- الـ Repository و Service يُنشآن بعد إنشاء Module الأساسي
- يمكن إنشاء Repository و Service لاحقاً باستخدام:
  ```bash
  php artisan module:make-repository RepositoryName ModuleName
  php artisan module:make-service ServiceName ModuleName
  php artisan module:make-interface InterfaceName ModuleName
  ```

---

تم تطوير هذه الميزات لتحسين تجربة العمل مع Laravel Modules Package! 🎉




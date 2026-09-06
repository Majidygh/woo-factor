# Woo Factor for WooCommerce

> افزونه فارسی صدور، مشاهده و چاپ فاکتور ووکامرس، همراه با پیش فاکتور و برچسب پستی.

[![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![WooCommerce 6.0+](https://img.shields.io/badge/WooCommerce-6.0%2B-96588A?logo=woocommerce&logoColor=white)](https://woocommerce.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> **وضعیت پروژه:** نسخه `1.1.0` در حال توسعه است. قبل از استفاده در فروشگاه عملیاتی، ابتدا روی staging یا یک کپی آزمایشی سایت بررسی کنید.

## قابلیت ها

- سه قالب فاکتور: **Classic**، **Modern** و **Commercial**
- فونت فارسی حرفه‌ای **Vazirmatn** به‌صورت self-hosted — بدون نیاز به اینترنت و CDN خارجی
- نمایش و چاپ فاکتور از صفحه سفارش در مدیریت ووکامرس
- لینک مشاهده/چاپ فاکتور در حساب کاربری مشتری و ایمیل های ووکامرس (لینک اختصاصی و امن هر سفارش)
- صدور فاکتور دستی از پنل مدیریت (با محافظت از منفی نشدن مبلغ نهایی)
- پیش فاکتور سبد خرید در صفحه تسویه حساب
- برچسب پستی قابل چاپ
- شماره فاکتور خودکار و پیشوند قابل تنظیم
- تاریخ شمسی و اعداد فارسی
- بارکد Code 128 برای شماره سفارش
- تنظیم لوگو، رنگ سازمانی، مشخصات فروشگاه، شناسه ملی، آدرس و متن پاورقی (درج امن رنگ در خروجی)
- فیلد کد ملی / شناسه ملی مشتری در checkout (ذخیره‌شده روی سفارش و درج در فاکتور)
- شورت کد نمایش لینک فاکتور: `[woo_factor_invoice order_id="123"]`

## نیازمندی‌ها

| مورد | حداقل نسخه |
| --- | --- |
| WordPress | نسخه پایدار پشتیبانی شده |
| WooCommerce | 6.0 |
| PHP | 7.4 |

## نصب

1. فایل ZIP افزونه را دانلود کنید یا این ریپو را clone کنید.
2. پوشه `woo-factor` را در مسیر `wp-content/plugins/` قرار دهید.
3. از **افزونه ها** در وردپرس، **Woo Factor for WooCommerce** را فعال کنید.
4. از منوی **فاکتورساز ووکامرس** تنظیمات فروشگاه، قالب و رنگ سازمانی را تکمیل کنید.
5. یک سفارش آزمایشی ثبت کنید و از صفحه سفارش، خروجی فاکتور و برچسب پستی را بررسی کنید.

### نصب با Git

```bash
cd /path/to/wordpress/wp-content/plugins
git clone https://github.com/Majidygh/woo-factor.git woo-factor
```

سپس افزونه را از پنل وردپرس فعال کنید.

## استفاده

### فاکتور سفارش

در صفحه ویرایش سفارش ووکامرس، عملیات **چاپ فاکتور** و **برچسب پستی** در دسترس است. مشتری نیز از بخش سفارش های حساب کاربری خود می تواند لینک دریافت فاکتور را ببیند.

### پیش فاکتور

در صفحه تسویه حساب، لینک چاپ پیش فاکتور سبد خرید نمایش داده می شود.

### شورت کد

```text
[woo_factor_invoice order_id="123"]
```

`order_id` باید شناسه یک سفارش معتبر ووکامرس باشد.

## توسعه محلی

این پروژه یک افزونه مستقل WordPress/WooCommerce است و build step ندارد. برای بررسی پایه PHP:

```bash
for f in $(git ls-files '*.php'); do php -l "$f" || exit 1; done
```

قبل از ارسال Pull Request، افزونه را در یک محیط واقعی WordPress + WooCommerce فعال کنید و حداقل این موارد را بررسی کنید:

- فعال سازی افزونه در کنار WooCommerce
- صدور و نمایش فاکتور برای سفارش آزمایشی
- هر سه قالب فاکتور
- برچسب پستی و پیش فاکتور
- نمایش موبایل و متن های طولانی فارسی

## ساختار پروژه

```text
woo-factor.php          Bootstrap افزونه و metadata
admin/                  تنظیمات، صدور دستی و meta boxها
includes/               منطق فاکتور، رندر، بارکد، تاریخ شمسی و hookها
templates/              قالب های فاکتور و برچسب پستی
assets/fonts/           فونت Vazirmatn (self-hosted) و استایل مشترک
tests/                  تست های ساده PHP (بدون نیاز به PHPUnit)
```

## امنیت و حریم خصوصی

- لینک مشاهده فاکتور برای هر سفارش با کلید اختصاصی سفارش ووکامرس (order key) محافظت می شود و قابل حدس نیست.
- اطلاعات سفارش و فاکتور می تواند شامل داده های شخصی مشتری باشد. دسترسی به محیط WordPress، پشتیبان ها و logها را محدود نگه دارید.
- **هیچ کلید API، رمز عبور یا اطلاعات مشتری را در Issue یا Pull Request عمومی ارسال نکنید.**
- برای گزارش آسیب پذیری، راهنمای [SECURITY.md](SECURITY.md) را بخوانید.

## مشارکت

مشارکت خوش آمد است. لطفاً پیش از شروع، [CONTRIBUTING.md](CONTRIBUTING.md) را بخوانید.

## مجوز

این پروژه تحت مجوز [MIT](LICENSE) منتشر شده است.

## سازنده

[مجید یعقوبی](https://github.com/Majidygh)

---

English project summary: Woo Factor is a Persian WooCommerce invoice, proforma, and shipping-label plugin. See the source and contribution guide for development details.

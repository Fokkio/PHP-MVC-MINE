# ใช้ PHP 8.2 พร้อม Apache
FROM php:8.2-apache

# ติดตั้ง Extension ที่จำเป็น (mysqli สำหรับต่อ Database)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# เปิดใช้งาน mod_rewrite (เพื่อให้ .htaccess ทำงานได้ สำหรับระบบ MVC)
RUN a2enmod rewrite

# ตั้งค่าให้ Apache มองไปที่โฟลเดอร์ public เป็นหน้าแรก (DocumentRoot)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# แก้ไข Config ของ Apache ให้ใช้ Path ใหม่ที่เราตั้งไว้
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy ไฟล์ทั้งหมดในโปรเจกต์ขึ้นไปบน Server
COPY . /var/www/html/

# ตั้งค่า Permission ให้ Apache อ่านไฟล์ได้
RUN chown -R www-data:www-data /var/www/html

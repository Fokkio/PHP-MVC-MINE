# ใช้ PHP 8.2 พร้อม Apache
FROM php:8.2-apache

# ติดตั้ง Extension ที่จำเป็น
RUN docker-php-ext-install mysqli pdo pdo_mysql

# เปิดใช้งาน mod_rewrite
RUN a2enmod rewrite

# ตั้งค่า Document Root ไปที่ public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# แก้ไข Config เดิมของ Apache เพื่อเปลี่ยน Document Root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 🔥 เพิ่มส่วนนี้: บังคับให้ Apache อ่านไฟล์ .htaccess (สำคัญมาก!)
RUN echo "<Directory /var/www/html/public>" >> /etc/apache2/apache2.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

# Copy ไฟล์ทั้งหมดขึ้น Server
COPY . /var/www/html/

# ตั้งค่า Permission
RUN chown -R www-data:www-data /var/www/html

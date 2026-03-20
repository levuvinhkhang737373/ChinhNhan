# Sử dụng image PHP 8.2 FPM (bạn có thể đổi version cho phù hợp với dự án)
FROM php:8.2-fpm

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Xóa cache để giảm dung lượng image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt các PHP extensions cần cho Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www//html

# Copy toàn bộ code vào container (tùy chọn, thường dùng khi deploy)
COPY . /var/www//html

# Cấp quyền cho thư mục (để tránh lỗi permission denied)
RUN chown -R www-data:www-data /var/www//html
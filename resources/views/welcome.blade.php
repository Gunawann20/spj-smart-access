<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kementerian Kependudukan Pembangunan Keluarga - Sistem Management Dokumen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .landing-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .landing-container {
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .landing-content {
            color: white;
            animation: slideInLeft 0.8s ease-out;
        }

        .landing-image {
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .landing-logo {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 2px solid rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
        }

        .landing-logo img {
            height: 40px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .landing-logo-text {
            display: flex;
            flex-direction: column;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }

        h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #bfdbfe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .landing-subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 15px;
            color: #bfdbfe;
        }

        .landing-description {
            font-size: 1.1rem;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }

        .feature-item {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.6);
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #f59e0b;
        }

        .feature-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 50px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
            color: white;
        }

        .action-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .qr-container {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .qr-container:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(59, 130, 246, 0.6);
            transform: translateY(-3px);
        }

        .qr-code-img {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
        }

        .qr-text {
            font-size: 0.95rem;
            color: #bfdbfe;
            font-weight: 500;
            line-height: 1.4;
            max-width: 150px;
        }

        .image-frame {
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: 3px solid rgba(59, 130, 246, 0.2);
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
        }

        .image-frame img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .landing-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            h1 {
                font-size: 2.5rem;
            }

            .landing-subtitle {
                font-size: 1.2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .action-container {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .cta-button {
                text-align: center;
            }

            .qr-container {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="landing-wrapper">
        <div class="landing-container">
            <!-- Left Content -->
            <div class="landing-content">
                <div class="landing-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BKKBN">
                    <div class="landing-logo-text">
                        <span>Kementerian Kependudukan</span>
                        <span>Pembangunan Keluarga</span>
                    </div>
                </div>

                <div class="landing-subtitle">Sistem Management Dokumen Pendukung</div>
                <p class="landing-description">
                    Platform terpercaya untuk mengelola dan menyimpan dokumen organisasi Anda dengan aman, efisien, dan terorganisir.
                </p>

                <!-- Features -->
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-lock"></i></div>
                        <div class="feature-text">Aman & Terpercaya</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <div class="feature-text">Upload Mudah</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-search"></i></div>
                        <div class="feature-text">Pencarian Cepat</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="action-container">
                    <a href="{{ route('login') }}" class="cta-button">
                        <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                    </a>

                    <div class="qr-container">
                        <img src="{{ asset('images/qr-code-copy.jpeg') }}" alt="QR Code Tutorial" class="qr-code-img">
                        <div class="qr-text">Scan QR untuk<br>Tutorial Penggunaan</div>
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="landing-image">
                <div class="image-frame">
                    <img src="{{ asset('images/frame.jpeg') }}" alt="Kementerian Kependudukan Pembangunan Keluarga - Sistem Management Dokumen">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>

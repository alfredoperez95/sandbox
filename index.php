<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 50%, #0d1b2a 100%);
            color: #e8e8e8;
            overflow-x: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
        }

        .hello {
            font-size: clamp(3rem, 12vw, 6rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(90deg, #00d9ff, #00ff88, #00d9ff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite, fadeInDown 0.8s ease-out;
        }

        .world {
            font-size: clamp(2rem, 8vw, 4rem);
            font-weight: 300;
            margin-top: 0.25rem;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .badge {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 600;
            background: rgba(0, 217, 255, 0.15);
            border: 1px solid rgba(0, 217, 255, 0.4);
            border-radius: 2rem;
            color: #00d9ff;
            animation: fadeIn 1s ease-out 0.5s both, pulse 2s ease-in-out infinite 1.5s;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            pointer-events: none;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: #00d9ff;
            top: 10%;
            left: 20%;
            animation: float 8s ease-in-out infinite;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: #00ff88;
            bottom: 20%;
            right: 15%;
            animation: float 6s ease-in-out infinite 1s;
        }

        @keyframes shimmer {
            to { background-position: 200% center; }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 0.9;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 217, 255, 0.3); }
            50% { box-shadow: 0 0 20px 5px rgba(0, 217, 255, 0.15); }
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <h1 class="hello">Hello</h1>
        <p class="world">World</p>
        <span class="badge"><?= date('d/m/Y · H:i'); ?> · PHP <?= phpversion(); ?></span>
    </div>
</body>
</html>

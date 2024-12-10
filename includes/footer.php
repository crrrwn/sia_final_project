</main>
    <footer>
    <style>
        footer {
            background-color: #16423C;
            color: #E9EFEC;
            padding: calc(var(--spacing-unit) * 2) 0;
            margin-top: calc(var(--spacing-unit) * 4);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-content p {
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: calc(var(--spacing-unit) * 1.5);
        }

        .social-links a {
            color: #C4DAD2;
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 1.5rem;
        }

        .social-links a:hover {
            color: #E9EFEC;
        }

        .footer-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: var(--spacing-unit);
        }

        .logo-image {
            width: 100px;
            height: auto;
            margin-bottom: calc(var(--spacing-unit) * 0.5);
        }

        .footer-logo p {
            margin: 0;
            text-align: center;
        }

        .footer-logo p:first-of-type {
            font-weight: bold;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: var(--spacing-unit);
            }

            .social-links {
                margin-top: var(--spacing-unit);
            }
            .footer-logo {
                margin-bottom: var(--spacing-unit);
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="/uploads/logobp.jpg" alt="Builders/Pandayan Logo" class="logo-image">
                <p>Builders/Pandayan</p>
                <p>The Official Blog Platform of MSU's Student Publication</p>
            </div>
            <p>&copy; <?php echo date("Y"); ?> Builders/Pandayan. All rights reserved.</p>
            <div class="social-links">
                <a href="https://www.facebook.com/TheBuildersPandayanPublication" title="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://www.tiktok.com/@thebuildersangpandayan" title="Tiktok"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.instagram.com/builderspandayan" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/@BuildersPandayan" title="Youtube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>


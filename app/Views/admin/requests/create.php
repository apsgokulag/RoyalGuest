<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Service Request</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .form-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .form-wrapper:hover::before {
            left: 100%;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from { text-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
            to { text-shadow: 0 0 30px rgba(118, 75, 162, 0.5); }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 16px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.1),
                0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-control:hover {
            border-color: rgba(102, 126, 234, 0.4);
            transform: translateY(-1px);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }

        label::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.3s ease;
        }

        .form-group:hover label::after {
            width: 100%;
        }

        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        .description-group {
            grid-column: 1 / -1;
        }

        .priority-indicator {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .priority-low { background: #10b981; }
        .priority-medium { background: #f59e0b; }
        .priority-high { background: #ef4444; }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: block;
            margin: 30px auto 0;
            min-width: 200px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 15px 35px rgba(102, 126, 234, 0.3),
                0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        .form-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
            transition: width 0.3s ease;
            z-index: 10;
        }

        .floating-label {
            position: absolute;
            left: 20px;
            top: 16px;
            color: #9ca3af;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .form-control:focus + .floating-label,
        .form-control:not(:placeholder-shown) + .floating-label {
            top: -8px;
            left: 16px;
            font-size: 12px;
            color: #667eea;
            background: white;
            padding: 0 8px;
            font-weight: 600;
        }

        .success-animation {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
        }

        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #10b981;
            position: relative;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .checkmark::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 40px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .form-wrapper {
                padding: 30px 20px;
                margin: 10px;
            }
            
            h2 {
                font-size: 2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        .tooltip:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="form-wrapper">
            <div class="form-progress" id="formProgress"></div>
            
            <h2>Create Service Request</h2>
            
            <form method="post" action="<?= site_url('admin/requests') ?>" id="serviceForm">
                <?= csrf_field() ?>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="guest_id">Guest Selection</label>
                        <select name="guest_id" id="guest_id" class="form-control" required>
                            <option value="">Select Guest</option>
                            <?php foreach ($guests as $guest): ?>
                            <option value="<?= $guest['id'] ?>">
                                <?= esc($guest['first_name'] . ' ' . $guest['last_name']) ?>
                                (Room <?= esc($guest['room_number']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service_type">Service Type</label>
                        <input type="text" name="service_type" id="service_type" class="form-control" required placeholder=" ">
                        <div class="floating-label">Enter service type</div>
                    </div>

                    <div class="form-group">
                        <label for="priority" class="tooltip" data-tooltip="Select request urgency level">Priority Level</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="">Select Priority</option>
                            <option value="low">🟢 Low Priority</option>
                            <option value="medium">🟡 Medium Priority</option>
                            <option value="high">🔴 High Priority</option>
                        </select>
                        <div class="priority-indicator" id="priorityIndicator"></div>
                    </div>

                    <div class="form-group">
                        <label for="status">Request Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending">⏳ Pending</option>
                            <option value="in_progress">🔄 In Progress</option>
                            <option value="completed">✅ Completed</option>
                            <option value="cancelled">❌ Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="assigned_to">Assign To Staff</label>
                        <select name="assigned_to" id="assigned_to" class="form-control">
                            <option value="">👤 Not Assigned</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">👨‍💼 <?= esc($user['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group description-group">
                        <label for="description">Detailed Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Provide detailed information about the service request..."></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Create Request</span>
                </button>
                <button  type="button" class="btn btn-secondary mt-3" onclick="window.location.href='<?= site_url('admin/requests') ?>'">
                    <span class="tooltip" data-tooltip="Go back to the requests list">Back to Requests</span>
                </button>
            </form>
        </div>
    </div>

    <div class="success-animation" id="successAnimation">
        <div class="checkmark" id="checkmark"></div>
    </div>

    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Form progress tracking
        function updateProgress() {
            const form = document.getElementById('serviceForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            const progressBar = document.getElementById('formProgress');
            
            let filledInputs = 0;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    filledInputs++;
                }
            });
            
            const progress = (filledInputs / inputs.length) * 100;
            progressBar.style.width = progress + '%';
        }

        // Priority indicator
        function updatePriorityIndicator() {
            const prioritySelect = document.getElementById('priority');
            const indicator = document.getElementById('priorityIndicator');
            
            indicator.className = 'priority-indicator';
            if (prioritySelect.value) {
                indicator.classList.add('priority-' + prioritySelect.value);
                indicator.style.opacity = '1';
            } else {
                indicator.style.opacity = '0';
            }
        }

        // Form submission with animation
        function handleSubmit(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const successAnimation = document.getElementById('successAnimation');
            const checkmark = document.getElementById('checkmark');
            
            // Button loading state
            submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            btnText.textContent = 'Creating Request...';
            submitBtn.disabled = true;
            
            // Simulate form submission delay
            setTimeout(() => {
                // Show success animation
                successAnimation.style.opacity = '1';
                successAnimation.style.pointerEvents = 'auto';
                checkmark.style.transform = 'scale(1)';
                
                // Reset button after animation
                setTimeout(() => {
                    submitBtn.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    btnText.textContent = 'Request Created Successfully!';
                    
                    // Hide success animation
                    setTimeout(() => {
                        successAnimation.style.opacity = '0';
                        successAnimation.style.pointerEvents = 'none';
                        checkmark.style.transform = 'scale(0)';
                        
                        // Reset form
                        document.getElementById('serviceForm').reset();
                        updateProgress();
                        updatePriorityIndicator();
                        
                        btnText.textContent = 'Create Request';
                        submitBtn.disabled = false;
                    }, 2000);
                }, 1000);
            }, 1500);
        }

        // Enhanced form interactions
        function enhanceFormInteractions() {
            const formControls = document.querySelectorAll('.form-control');
            
            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                    this.parentElement.style.zIndex = '10';
                });
                
                control.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                    this.parentElement.style.zIndex = '1';
                });
                
                control.addEventListener('input', updateProgress);
                control.addEventListener('change', updateProgress);
            });
        }

        // Parallax effect for form wrapper
        function addParallaxEffect() {
            const formWrapper = document.querySelector('.form-wrapper');
            
            document.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth) * 100;
                const y = (e.clientY / window.innerHeight) * 100;
                
                formWrapper.style.transform = `perspective(1000px) rotateX(${(y - 50) * 0.1}deg) rotateY(${(x - 50) * 0.1}deg)`;
            });
            
            document.addEventListener('mouseleave', () => {
                formWrapper.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            });
        }

        // Typing effect for placeholder text
        function addTypingEffect() {
            const serviceTypeInput = document.getElementById('service_type');
            const descriptions = [
                'Room cleaning service',
                'Maintenance request',
                'Food delivery',
                'Laundry service',
                'Technical support',
                'Concierge assistance'
            ];
            
            let currentIndex = 0;
            let currentText = '';
            let isDeleting = false;
            
            function typeEffect() {
                const fullText = descriptions[currentIndex];
                
                if (isDeleting) {
                    currentText = fullText.substring(0, currentText.length - 1);
                } else {
                    currentText = fullText.substring(0, currentText.length + 1);
                }
                
                serviceTypeInput.placeholder = currentText;
                
                let typeSpeed = isDeleting ? 50 : 100;
                
                if (!isDeleting && currentText === fullText) {
                    typeSpeed = 2000;
                    isDeleting = true;
                } else if (isDeleting && currentText === '') {
                    isDeleting = false;
                    currentIndex = (currentIndex + 1) % descriptions.length;
                    typeSpeed = 500;
                }
                
                setTimeout(typeEffect, typeSpeed);
            }
            
            // Only start typing effect if input is not focused
            if (document.activeElement !== serviceTypeInput) {
                typeEffect();
            }
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            updateProgress();
            enhanceFormInteractions();
            addParallaxEffect();
            addTypingEffect();
            
            // Event listeners
            document.getElementById('priority').addEventListener('change', updatePriorityIndicator);
            document.getElementById('serviceForm').addEventListener('submit', handleSubmit);
            
            // Initial progress update
            setTimeout(updateProgress, 100);
        });

        // Add scroll-triggered animations
        function addScrollAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            });
            
            document.querySelectorAll('.form-group').forEach(group => {
                observer.observe(group);
            });
        }

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                const focusedElement = document.activeElement;
                if (focusedElement.classList.contains('form-control')) {
                    focusedElement.parentElement.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        focusedElement.parentElement.style.transform = 'scale(1)';
                    }, 200);
                }
            }
        });

        // Add sound effects (optional - commented out as it might be intrusive)
        
        function playSound(type) {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            const frequencies = {
                click: 800,
                success: 600,
                focus: 400
            };
            
            oscillator.frequency.setValueAtTime(frequencies[type], audioContext.currentTime);
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        }
    </script>
</body>
</html>
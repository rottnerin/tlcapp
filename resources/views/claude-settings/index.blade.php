<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Claude Code Settings</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --claude-purple: #6B46C1;
            --claude-purple-dark: #553C9A;
            --claude-green: #10B981;
            --claude-blue: #3B82F6;
            --claude-orange: #F59E0B;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: var(--claude-purple);
            color: white;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            background: #f9fafb;
        }
        .tab {
            padding: 1rem 2rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
        }
        .tab:hover {
            background: #f3f4f6;
        }
        .tab.active {
            border-bottom-color: var(--claude-purple);
            color: var(--claude-purple);
            background: white;
        }
        .content {
            padding: 2rem;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .sound-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .sound-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .sound-card:hover {
            border-color: var(--claude-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 70, 193, 0.2);
        }
        .sound-card.selected {
            border-color: var(--claude-purple);
            background: #f5f3ff;
        }
        .sound-card.system {
            border-left: 4px solid var(--claude-blue);
        }
        .sound-card.custom {
            border-left: 4px solid var(--claude-green);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: var(--claude-purple);
            color: white;
        }
        .btn-primary:hover {
            background: var(--claude-purple-dark);
        }
        .btn-success {
            background: var(--claude-green);
            color: white;
        }
        .btn-danger {
            background: #EF4444;
            color: white;
        }
        .btn-secondary {
            background: #6B7280;
            color: white;
        }
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .upload-area:hover {
            border-color: var(--claude-purple);
            background: #f5f3ff;
        }
        .hook-selector {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 12px;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .current-settings {
            background: #f9fafb;
            border-left: 4px solid var(--claude-purple);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.875rem;
        }
        .delete-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #EF4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .sound-card:hover .delete-btn {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-robot fa-3x"></i>
            <div>
                <h1 style="margin: 0; font-size: 2rem;">Claude Code Settings</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Configure hooks and sound notifications</p>
            </div>
        </div>

        <div id="alert-container"></div>

        <div class="tabs">
            <div class="tab active" data-tab="hooks">
                <i class="fas fa-bolt"></i> Hooks
            </div>
            <div class="tab" data-tab="sounds">
                <i class="fas fa-volume-up"></i> Sounds
            </div>
            <div class="tab" data-tab="upload">
                <i class="fas fa-upload"></i> Upload Custom Sounds
            </div>
        </div>

        <div class="content">
            <!-- Hooks Tab -->
            <div class="tab-content active" id="hooks-tab">
                <h2>Configure Hooks</h2>
                <p style="color: #6b7280; margin-bottom: 2rem;">Choose when Claude should play sounds based on different events.</p>

                <div class="current-settings">
                    <strong>Current Settings:</strong>
                    <pre id="current-settings-display" style="margin-top: 0.5rem; white-space: pre-wrap;">Loading...</pre>
                </div>

                <div class="hook-selector">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--claude-green);"></i> afterToolResult
                        <span style="color: #6b7280; font-weight: normal; font-size: 0.875rem;"> - Plays after each tool execution</span>
                    </label>
                    <select id="hook-afterToolResult" class="hook-select" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="">None (Silent)</option>
                    </select>
                </div>

                <div class="hook-selector">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-play-circle" style="color: var(--claude-blue);"></i> beforeToolUse
                        <span style="color: #6b7280; font-weight: normal; font-size: 0.875rem;"> - Plays before executing a tool</span>
                    </label>
                    <select id="hook-beforeToolUse" class="hook-select" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="">None (Silent)</option>
                    </select>
                </div>

                <div class="hook-selector">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        <i class="fas fa-keyboard" style="color: var(--claude-orange);"></i> userPromptSubmit
                        <span style="color: #6b7280; font-weight: normal; font-size: 0.875rem;"> - Plays when you submit a prompt</span>
                    </label>
                    <select id="hook-userPromptSubmit" class="hook-select" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="">None (Silent)</option>
                    </select>
                </div>

                <button onclick="saveSettings()" class="btn btn-success" style="margin-top: 1rem;">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <button onclick="testSound()" class="btn btn-secondary" style="margin-top: 1rem;">
                    <i class="fas fa-play"></i> Test Current Sound
                </button>
            </div>

            <!-- Sounds Tab -->
            <div class="tab-content" id="sounds-tab">
                <h2>Available Sounds</h2>
                <p style="color: #6b7280; margin-bottom: 1rem;">Click any sound to preview it.</p>

                <h3 style="margin-top: 2rem;"><i class="fas fa-apple-alt" style="color: var(--claude-blue);"></i> System Sounds</h3>
                <div id="system-sounds" class="sound-grid">
                    <div style="text-align: center; padding: 2rem; color: #9ca3af;">Loading sounds...</div>
                </div>

                <h3 style="margin-top: 2rem;"><i class="fas fa-star" style="color: var(--claude-green);"></i> Custom Sounds</h3>
                <div id="custom-sounds" class="sound-grid">
                    <div style="text-align: center; padding: 2rem; color: #9ca3af;">No custom sounds yet. Upload some in the Upload tab!</div>
                </div>
            </div>

            <!-- Upload Tab -->
            <div class="tab-content" id="upload-tab">
                <h2>Upload Custom Sounds</h2>
                <p style="color: #6b7280; margin-bottom: 2rem;">Upload your own sound files (AIFF, WAV, MP3, M4A). Max 10MB.</p>

                <div class="upload-area" onclick="document.getElementById('sound-upload').click()">
                    <i class="fas fa-cloud-upload-alt fa-3x" style="color: var(--claude-purple); margin-bottom: 1rem;"></i>
                    <p style="font-weight: 600; margin-bottom: 0.5rem;">Click to upload or drag and drop</p>
                    <p style="color: #9ca3af; font-size: 0.875rem;">Supported formats: AIFF, WAV, MP3, M4A</p>
                    <input type="file" id="sound-upload" accept=".aiff,.wav,.mp3,.m4a" style="display: none;" onchange="uploadSound(this)">
                </div>
            </div>
        </div>
    </div>

    <script>
        let allSounds = [];
        let currentSettings = {};

        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab + '-tab').classList.add('active');
            });
        });

        // Load sounds and settings on page load
        async function init() {
            await loadSounds();
            await loadSettings();
        }

        async function loadSounds() {
            const response = await fetch('/claude-settings/api/sounds');
            const sounds = await response.json();
            allSounds = [...sounds.system, ...sounds.custom];

            // Populate system sounds
            const systemContainer = document.getElementById('system-sounds');
            systemContainer.innerHTML = sounds.system.map(sound => `
                <div class="sound-card system" onclick="playSound('${sound.path}')">
                    <i class="fas fa-volume-up"></i>
                    <div style="font-weight: 600; margin-top: 0.5rem;">${sound.name}</div>
                    <div style="font-size: 0.75rem; color: #9ca3af;">System</div>
                </div>
            `).join('');

            // Populate custom sounds
            const customContainer = document.getElementById('custom-sounds');
            if (sounds.custom.length > 0) {
                customContainer.innerHTML = sounds.custom.map(sound => `
                    <div class="sound-card custom" onclick="playSound('${sound.path}')">
                        <button class="delete-btn" onclick="event.stopPropagation(); deleteSound('${sound.path}')">
                            <i class="fas fa-times"></i>
                        </button>
                        <i class="fas fa-music"></i>
                        <div style="font-weight: 600; margin-top: 0.5rem;">${sound.name}</div>
                        <div style="font-size: 0.75rem; color: #9ca3af;">Custom</div>
                    </div>
                `).join('');
            }

            // Populate hook selects
            const selects = document.querySelectorAll('.hook-select');
            selects.forEach(select => {
                select.innerHTML = '<option value="">None (Silent)</option>' +
                    allSounds.map(s => `<option value="afplay ${s.path} &">${s.name}</option>`).join('');
            });
        }

        async function loadSettings() {
            const response = await fetch('/claude-settings/api/settings');
            currentSettings = await response.json();

            // Display current settings
            document.getElementById('current-settings-display').textContent =
                JSON.stringify(currentSettings, null, 2);

            // Set hook selects
            if (currentSettings.hooks) {
                Object.keys(currentSettings.hooks).forEach(hook => {
                    const select = document.getElementById(`hook-${hook}`);
                    if (select) {
                        select.value = currentSettings.hooks[hook] || '';
                    }
                });
            }
        }

        async function playSound(path) {
            await fetch('/claude-settings/api/sounds/play', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ path })
            });
        }

        async function testSound() {
            const afterToolResult = document.getElementById('hook-afterToolResult').value;
            if (!afterToolResult) {
                showAlert('Please select a sound for afterToolResult hook first', 'error');
                return;
            }
            const match = afterToolResult.match(/afplay ([^ ]+)/);
            if (match) {
                await playSound(match[1]);
                showAlert('Playing test sound!', 'success');
            }
        }

        async function saveSettings() {
            const hooks = {
                afterToolResult: document.getElementById('hook-afterToolResult').value,
                beforeToolUse: document.getElementById('hook-beforeToolUse').value,
                userPromptSubmit: document.getElementById('hook-userPromptSubmit').value
            };

            const response = await fetch('/claude-settings/api/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ hooks })
            });

            const result = await response.json();
            if (result.success) {
                showAlert('Settings saved successfully! Restart Claude Code for changes to take effect.', 'success');
                await loadSettings();
            }
        }

        async function uploadSound(input) {
            if (!input.files[0]) return;

            const formData = new FormData();
            formData.append('sound', input.files[0]);

            const response = await fetch('/claude-settings/api/sounds/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                showAlert('Sound uploaded successfully!', 'success');
                await loadSounds();
                input.value = '';
            } else {
                showAlert('Failed to upload sound', 'error');
            }
        }

        async function deleteSound(path) {
            if (!confirm('Are you sure you want to delete this custom sound?')) return;

            const response = await fetch('/claude-settings/api/sounds/delete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ path })
            });

            const result = await response.json();
            if (result.success) {
                showAlert('Sound deleted successfully!', 'success');
                await loadSounds();
            }
        }

        function showAlert(message, type) {
            const container = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            alert.style.margin = '1rem';
            container.appendChild(alert);
            setTimeout(() => alert.remove(), 5000);
        }

        // Initialize on page load
        init();
    </script>
</body>
</html>

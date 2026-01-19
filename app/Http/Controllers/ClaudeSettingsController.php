<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ClaudeSettingsController extends Controller
{
    private $settingsPath;
    private $customSoundsPath;

    public function __construct()
    {
        // Get home directory reliably across different environments
        $homeDir = getenv('HOME') ?: (getenv('USERPROFILE') ?: posix_getpwuid(posix_geteuid())['dir']);
        $this->settingsPath = $homeDir . '/.config/claude/settings.json';
        $this->customSoundsPath = storage_path('app/claude-sounds');

        // Ensure directories exist
        if (!File::exists(dirname($this->settingsPath))) {
            File::makeDirectory(dirname($this->settingsPath), 0755, true);
        }
        if (!File::exists($this->customSoundsPath)) {
            File::makeDirectory($this->customSoundsPath, 0755, true);
        }
    }

    public function index()
    {
        return view('claude-settings.index');
    }

    public function getSettings()
    {
        if (File::exists($this->settingsPath)) {
            $settings = json_decode(File::get($this->settingsPath), true);
        } else {
            $settings = ['hooks' => []];
        }

        return response()->json($settings);
    }

    public function saveSettings(Request $request)
    {
        $settings = $request->validate([
            'hooks' => 'required|array',
            'hooks.*' => 'nullable|string'
        ]);

        File::put($this->settingsPath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return response()->json(['success' => true, 'message' => 'Settings saved successfully!']);
    }

    public function listSounds()
    {
        // System sounds
        $systemSounds = [];
        $systemSoundsPath = '/System/Library/Sounds';

        if (File::exists($systemSoundsPath)) {
            $files = File::files($systemSoundsPath);
            foreach ($files as $file) {
                if (in_array($file->getExtension(), ['aiff', 'wav', 'mp3'])) {
                    $systemSounds[] = [
                        'name' => $file->getFilenameWithoutExtension(),
                        'path' => $file->getPathname(),
                        'type' => 'system'
                    ];
                }
            }
        }

        // Custom sounds
        $customSounds = [];
        if (File::exists($this->customSoundsPath)) {
            $files = File::files($this->customSoundsPath);
            foreach ($files as $file) {
                if (in_array($file->getExtension(), ['aiff', 'wav', 'mp3', 'm4a'])) {
                    $customSounds[] = [
                        'name' => $file->getFilenameWithoutExtension(),
                        'path' => $file->getPathname(),
                        'type' => 'custom'
                    ];
                }
            }
        }

        return response()->json([
            'system' => $systemSounds,
            'custom' => $customSounds
        ]);
    }

    public function playSound(Request $request)
    {
        $soundPath = $request->input('path');

        if (!File::exists($soundPath)) {
            return response()->json(['error' => 'Sound file not found'], 404);
        }

        // Play sound in background
        exec("afplay " . escapeshellarg($soundPath) . " > /dev/null 2>&1 &");

        return response()->json(['success' => true]);
    }

    public function uploadSound(Request $request)
    {
        $request->validate([
            'sound' => 'required|file|mimes:aiff,wav,mp3,m4a|max:10240' // 10MB max
        ]);

        $file = $request->file('sound');
        $filename = $file->getClientOriginalName();
        $file->move($this->customSoundsPath, $filename);

        return response()->json([
            'success' => true,
            'message' => 'Sound uploaded successfully!',
            'sound' => [
                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'path' => $this->customSoundsPath . '/' . $filename,
                'type' => 'custom'
            ]
        ]);
    }

    public function deleteSound(Request $request)
    {
        $soundPath = $request->input('path');

        // Only allow deleting custom sounds
        if (!str_contains($soundPath, $this->customSoundsPath)) {
            return response()->json(['error' => 'Cannot delete system sounds'], 403);
        }

        if (File::exists($soundPath)) {
            File::delete($soundPath);
            return response()->json(['success' => true, 'message' => 'Sound deleted successfully!']);
        }

        return response()->json(['error' => 'Sound not found'], 404);
    }
}

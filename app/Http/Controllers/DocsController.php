<?php

namespace App\Http\Controllers;

use Github\Client;

class DocsController extends Controller
{
    public function changelog()
    {
        $client = new Client();

        $owner = env('GITHUB_OWNER', 'd-arsya');
        $repo = env('GITHUB_REPO', 'Appiks.id-Backend');
        $branch = env('GITHUB_BRANCH', 'main');

        // Ambil daftar commit
        $commits = $client->api('repo')->commits()->all($owner, $repo, [
            'sha' => $branch
        ]);

        $grouped = [];

        foreach ($commits as $commit) {
            $sha = $commit['sha'];
            $message = $commit['commit']['message'];

            // Ambil detail commit (termasuk file)
            $detail = $client->api('repo')->commits()->show($owner, $repo, $sha);

            $files = collect($detail['files'])->map(function ($file) {
                return [
                    'name' => $file['filename'],
                    'status' => $file['status'],
                    'additions' => $file['additions'],
                    'deletions' => $file['deletions'],
                    'url' => $file['blob_url']
                ];
            });

            // Ambil keyword dari pesan commit
            if (preg_match('/\[(.*?)\]\s*(.*)/', $message, $matches)) {
                $keyword = strtoupper($matches[1]);
                $desc = $matches[2];
            } else {
                $keyword = 'OTHER';
                $desc = $message;
            }

            $grouped[] = [
                'keyword' => $keyword,
                'desc' => $desc,
                'sha' => $sha,
                'url' => $commit['html_url'],
                'author' => $commit['commit']['author']['name'],
                'date' => $commit['commit']['author']['date'],
                'files' => $files
            ];
        }
        return view('changelog', compact('grouped'));
    }
}

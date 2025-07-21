<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ChatStreamController extends Controller
{

    public function index(): JsonResponse
    {
        $text = <<<'TEXT'
Certainly! Here's a simple example of an HTML webpage: ```html
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>My Sample Page</title>
</head>

<body>
	<h1>Welcome to My Website</h1>
	<p>This is a paragraph of text to demonstrate HTML structure.</p>
	<ul>
		<li>First item</li>
		<li>Second item</li>
		<li>Third item</li>
	</ul>
	<a href="https://www.example.com">Visit Example.com</a>
</body>

</html>
``` Would you like a more advanced example or a specific feature?
TEXT;

        return response()->json([
            'message' => $text,
        ]);
    }
}

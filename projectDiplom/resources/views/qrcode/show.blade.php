<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR-код для стола</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-center mb-6">QR-код для стола №{{ $table->table_number }}</h1>
                
                <div class="flex flex-col items-center justify-center mb-8">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                        <img src="{{ $qrCodeUrl }}" alt="QR-код для стола" class="w-64 h-64">
                    </div>
                    
                    <p class="text-gray-600 text-center mb-2">
                        Этот QR-код ведет на страницу: <a href="{{ $frontendUrl }}" class="text-blue-600 hover:underline" target="_blank">{{ $frontendUrl }}</a>
                    </p>
                    
                    <p class="text-gray-500 text-sm text-center mb-6">
                        Разместите этот QR-код на столе, чтобы посетители могли сканировать его и делать заказы.
                    </p>
                    
                    <div class="flex space-x-4">
                        <a href="{{ $qrCodeUrl }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 transition" download="qr-code-table-{{ $table->table_number }}.png">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Скачать QR-код
                        </a>
                        
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Распечатать
                        </button>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold mb-4">Информация о столе</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600"><span class="font-medium">Ресторан:</span> {{ $table->restaurant->name }}</p>
                            <p class="text-gray-600"><span class="font-medium">Номер стола:</span> {{ $table->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600"><span class="font-medium">Вместимость:</span> {{ $table->capacity }} чел.</p>
                            <p class="text-gray-600"><span class="font-medium">Статус:</span> 
                                <span class="px-2 py-1 text-xs rounded-full {{ $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $status === 'active' ? 'Активен' : 'Неактивен' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .container, .container * {
                visibility: visible;
            }
            .container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</body>
</html>
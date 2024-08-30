<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('User Table')</title>
    <style>
        body {
            font-family: 'examplefont', sans-serif;
        }
    </style>
</head>
<body>

<div style="">
    <div style="border: 2px solid #007bff; padding: 10px; border-radius: 10px; background-color: #f8f9fa; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div style="">

                <div style="text-align: center; align-items: center;">
                    <a href="{{setup('name', 'starter')}}" style="flex: 1; ">
                        <img src="{{ getSettingImage('iconImage', 'icon') }}" alt="" onerror="{{ getErrorImage() }}" style="width: 40px ">
                    </a>
                    <center><a href="{{setup('name', 'starter')}}" style="font-size: 18px; font-weight: bold;">{{setup('name', 'starter')}}</a></center>

                    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                        <table style="border-collapse: collapse; width: 600px; border: 1px solid #ccc;">
                            <tr>
                                <td colspan="2" style="background-color: #f0f0f0; padding: 20px; border: 1px solid #ccc;">
                                    <h2 style="margin: 0;">@lang('users table')</h2>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; padding: 10px; border: 1px solid #ccc;">
                                    <h3 style="margin: 0;">@lang('Admin'): {{setup('name', 'starter')}}</h3>
                                    <p style="margin: 5px 0;">@lang('Address'): {{setup('address', 'address')}}</p>
                                    <p style="margin: 5px 0;">@lang('Phone'): {{setup('phone', 'starter')}}</p>
                                    <p style="margin: 5px 0;">@lang('Email'): {{setup('email', 'starter')}}</p>
                                </td>
                                <td style="width: 50%; padding: 10px; border: 1px solid #ccc;">
                                    <h3 style="margin: 0;">Bill To</h3>
                                    <p style="margin: 5px 0;">Customer Name</p>
                                    <p style="margin: 5px 0;">Address: Customer Address</p>
                                    <p style="margin: 5px 0;">City, Country, Postal Code</p>
                                    <p style="margin: 5px 0;">Phone: +987654321</p>
                                    <p style="margin: 5px 0;">Email: customer@example.com</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
        </div>
        <div style="">

            <h2 style="text-align: center;">@lang('User Table')</h2>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;text-transform: capitalize">
                <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('ID')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('Name')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('Email')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('Type')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('Status')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('phone')</th>
                    <th style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">@lang('address')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $i => $item)

                    <tr style="border: 1px solid #ddd;">
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->id}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->name}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->email}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->role->name}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->status}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{$item->phone}}</td>
                        <td style="text-transform: capitalize; padding: 10px; border: 1px solid #ddd;">{{str($item->address)->words(5)}}</td>
                    </tr>
                @empty

                @endforelse


                </tbody>
            </table>

        </div>

    </div>

</div>
</body>
</html>

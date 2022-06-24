$pass = Hash::make(123456789);
User::create([
'name' => 'admin',
'email' => 'admin@gmail.com',
'mobile' => '1234567890',
'address' => 'test',
'country' => 'India',
'state' => 'Rajasthan',
'city' => 'Udaipur',
'pincode' => '313001',
'password' => $pass,
'role_id' => 1,
'profile_image' => '',
]);

StaticValueController@index
StaticValueController@create
StaticValueController@edit
StaticValueController@destroy

INSERT INTO `permissions`(`id`, `title`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (NULL,'Static Value','StaticValueController@index','web','2022-06-23 00:06:29','2022-06-23 00:06:29'),(NULL,'Static Value','StaticValueController@create','web','2022-06-23 00:06:29','2022-06-23 00:06:29'),(NULL,'Static Value','StaticValueController@edit','web','2022-06-23 00:06:29','2022-06-23 00:06:29'),(NULL,'Static Value','StaticValueController@destroy','web','2022-06-23 00:06:29','2022-06-23 00:06:29');
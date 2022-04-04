
        $pass = Hash::make(123456789);
        User::create([
            'name'                  =>  'admin',
            'email'                 =>  'admin@gmail.com',
            'mobile'                =>  '1234567890',
            'address'               =>  'test',
            'country'               =>  'India',
            'state'                 =>  'Rajasthan',
            'city'                  =>  'Udaipur',
            'pincode'               =>  '313001',
            'password'              =>  $pass,
            'role_id'               =>  1,
            'profile_image'         =>  '',
        ]);
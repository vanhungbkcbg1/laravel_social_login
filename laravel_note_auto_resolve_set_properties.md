when 1 object cua class or abstract class được resolve , nó sẽ chạy qua một số call back
chúng ta có thể tận dụng được cái call back này để set một số thuộc tính cho object đó
ví dụ trong \App\Http\Controllers\DemoRequestController chúng ta có khai báo là sử dụng SimpleLib

trong AppServiceProvider chúng ta đăng kí thêm event resolving của class SimpleLib
và thực hiện set giá trị container cho object

việc gọi event sau khi resolve được object , ở trong method \Illuminate\Container\Container::resolve
````
if ($raiseEvents) {
            $this->fireResolvingCallbacks($abstract, $object);
        }
````

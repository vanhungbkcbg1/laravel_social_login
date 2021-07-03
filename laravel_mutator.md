## trong laravel có cung cấp phương thức gọi là mutator
### 1. Set Mutator cho thuộc tính của model
#### format chung nhu sau
##### set{attribute_name}Attribute
việc thực hiện set giá trị cho properties của object đều thông qua hàm _set
trong hàm, hàm này nó sẽ gọi tới hàm \Illuminate\Database\Eloquent\Concerns\HasAttributes::setAttribute
 hàm này nó sẽ thực hiện check xem thuộc tính đấy có định nghĩa mutator trong class hay không
nếu có thì nó sẽ call hàm đấy để set, nếu ko thì set bt

dưới đây là code trong laravel Model core

##### code của hàm _set
````
/**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
````

#####code của hàm setAttribute
````
/**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the connection grammar's date format. We will auto set the values.
        elseif ($value && $this->isDateAttribute($key)) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isJsonCastable($key) && ! is_null($value)) {
            $value = $this->castAttributeAsJson($key, $value);
        }

        // If this attribute contains a JSON ->, we'll set the proper value in the
        // attribute's underlying array. This takes care of properly nesting an
        // attribute in the array's value in the case of deeply nested items.
        if (Str::contains($key, '->')) {
            return $this->fillJsonAttribute($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }
````

##### sample định nghĩa set mutator trong laravel Post Model
````
public function setTitleAttribute($value){
        $this->attributes['title'] = strtoupper($value);
    }

doan code dưới đây sẽ gọi lên hàm setTitleAttribute ở trên
$post=new Post();
$post->title ="abc";

````

### 2. Get Mutator cho thuộc tính của model
#### format chung
##### get{attribute_name}Attribute
việc thực hiện get thuộc tính của model, nó được gọi vào hàm _get trong Model base của laravel

````
/**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
````

code cua hàm getAttribute

````
/**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }

        // Here we will determine if the model base class itself contains this given key
        // since we don't want to treat any of those methods as relationships because
        // they are all intended as helper methods and none of these are relations.
        if (method_exists(self::class, $key)) {
            return;
        }

        return $this->getRelationValue($key);
    }
````

sample của hàm get mutator cho thuộc tính title của model Post
````
    public function getTitleAttribute($value){
        return strtolower($value);
    }
````


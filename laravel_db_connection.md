#### \Illuminate\Database\DatabaseServiceProvider phục vụ đăng kí các service về db vào container

trong class nay co set resolve cho model bằng đoạn code sau
````
/**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);

        Model::setEventDispatcher($this->app['events']);
    }
````

solve sẽ được dùng về sau
ví dụ resolve được dùng để lấy connection cho việc khởi tạo EloquentBuilder trong model
````
 /**
     * Get a new query builder that doesn't have any global scopes or eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newModelQuery()
    {
        return $this->newEloquentBuilder(
            $this->newBaseQueryBuilder()
        )->setModel($this);
    }
    
````
code cua hàm new BaseQueryBuilder

````
    /**
         * Get a new query builder instance for the connection.
         *
         * @return \Illuminate\Database\Query\Builder
         */
        protected function newBaseQueryBuilder()
        {
            return $this->getConnection()->query();
        }
````

code của hàm getConnection
````
/**
     * Get the database connection for the model.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return static::resolveConnection($this->getConnectionName());
    }
````

code cua ham resolveConnection sẽ dùng lại resolver của Model
````
/**
     * Resolve a connection instance.
     *
     * @param  string|null  $connection
     * @return \Illuminate\Database\Connection
     */
    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection($connection);
    }
````


PHP SafeInvoker
====================================================================================================

PHP Error/Warning/Notice などが発生したときに例外を送出するためのユーティリティライブラリ。


Requirements
----------------------------------------------------------------------------------------------------

- PHP 5.3.3 or later


Installation
----------------------------------------------------------------------------------------------------

Using Composer.

```console
$ php composer.phar require "ngyuki/php-safe-invoker *"
```

Usage
----------------------------------------------------------------------------------------------------

**以前の版のままなので正しくない**

Safe::call() にコールバック関数を渡すと、そのコールバック関数を実行します。
そのとき、エラーハンドラを自動的に設定して PHP Warning などの PHP エラーが発生したら
`ngyuki\SafeInvoker\Exception\ErrorException` 例外をスローします。

例えば次のように `mkdir` を呼び出すと、ディレクトリの作成に失敗したときの PHP Warning をトリガに例外がスローされるため、
戻り値を無視して例外ベースで処理を記述することが出来ます。

```php
<?php
use ngyuki\SafeInvoker\Safe;
use ngyuki\SafeInvoker\Exception\ErrorException;

try
{
    Safe::call(function(){
        // ディレクトリを作成
        mkdir('/path/to/dir');
    });
    
    // ディレクトリの作成に失敗すると例外がスローされるためここは実行されません。
    echo "ok\n";
}
catch (ErrorException $ex)
{
    $class = get_class($ex);
    $message = $ex->getMessage();

    // ngyuki\SafeInvoker\Exception\ErrorException: mkdir(): No such file or directory
    echo "$class: $message\n";
}
```

しかし、上のコードには問題があります。

mkdir でディレクトリの作成に失敗したときに PHP Warning が発生することは保証されていないため(多分)、
ディレクトリ作成に失敗したにも関わらず "ok" と出力される可能性があります。

よって、確実にするなら次のように戻り値の検査も必要です。

```php
<?php
use ngyuki\SafeInvoker\Safe;
use ngyuki\SafeInvoker\Exception\ErrorException;

try
{
    $ret = Safe::call(function(){
        // ディレクトリを作成
        mkdir('/path/to/dir');
    });
    
    if ($ret === false)
    {
        throw new \UnexpectedValueException('unknown error.');
    }
    
    echo "ok\n";
}
catch (ErrorException $ex)
{
    ...
}
catch (\UnexpectedValueException $ex)
{
    ...
}
```

なんだか普通に書くより冗長になってしまいました・・・

次のように `Safe::be()` を使えば戻り値の検査も一緒に行えます。

が、これでは普通に書くよりも記述量が増えています。そこで、次のように戻り値の検査を行います。

```php
<?php
use ngyuki\SafeInvoker\Safe;
use ngyuki\SafeInvoker\Exception\ErrorException;
use ngyuki\SafeInvoker\Exception\AssertionException;

try
{
    Safe::call(function(){
        // ディレクトリを作成
        mkdir('/path/to/dir');
    }) === false or Safe::raise();
    
    echo "ok\n";
    }
catch (ErrorException $ex)
{
    ...
}
catch (AssertionException $ex)
{
    ...
}
```

```php
<?php
use ngyuki\SafeInvoker\Invoker;
use ngyuki\SafeInvoker\Exception\ErrorException;
use ngyuki\SafeInvoker\Exception\FailedException;

try
{
    // ディレクトリを作成
    Invoker::mkdir('/path/to/nocreate') === false or Invoker::raise();
    
    // ディレクトリの作成に失敗すると例外がスローされるためここは実行されません。
    echo "ok";
}
catch (ErrorException $ex)
{
    // PHP Warning
}
catch (FailedException $ex)
{
    // 戻り値の検査に失敗
}
```

`ngyuki\SafeInvoker\Exception\FailedException` と `ngyuki\SafeInvoker\Exception\FailedException` はどちらも
`ngyuki\SafeInvoker\Exception\ExceptionInterface` をインプリメントしているため、区別する必要が無いなら次のようにも書けます。

```php
<?php
use ngyuki\SafeInvoker\Invoker;
use ngyuki\SafeInvoker\Exception\ExceptionInterface;

try
{
    // ディレクトリを作成
    Invoker::mkdir('/path/to/nocreate') === false or Invoker::raise();
    
    // ディレクトリの作成に失敗すると例外がスローされるためここは実行されません。
    echo "ok";
}
catch (ExceptionInterface $ex)
{
    ....
}
```

----------------------------------------------------------------------------------------------------

ここまでの例では `Invoker` は静的メソッド呼び出ししかしていませんでしたが、`Invoker` はインスタンス化することも出来ます。

インスタンスメソッドの呼び出しでも同名のグローバル関数が呼び出されます。

```php
<?php
use ngyuki\SafeInvoker\Invoker;
use ngyuki\SafeInvoker\Exception\ExceptionInterface;

// インスタンス化
$invoker = new Invoker;

try
{
    // ディレクトリを作成
    $invoker::assert($invoker->mkdir('/path/to/nocreate') === false);
    
    // ディレクトリの作成に失敗すると例外がスローされるためここは実行されません。
    echo "ok";
}
catch (ExceptionInterface $ex)
{
    ....
}
```

インスタンス化すれば Phake などのモッキングフレームワークでモック（スタブ）に出来ます。

例えば、次のように `time()` 関数をモック化できます。

```php
<?php
use Phake;
use ngyuki\SafeInvoker\Invoker;
use ngyuki\SafeInvoker\Exception\ErrorException;

$mock = Phake::partialMock(get_class(new Invoker));
Phake::when($mock)->time()->thenReturn(1234567890);
```


License
----------------------------------------------------------------------------------------------------

- [MIT License](http://www.opensource.org/licenses/mit-license.php)


Todo
----------------------------------------------------------------------------------------------------

### 例外にするエラーレベルを指定する機能

`Invoker` のインスタンス作成後にアドホックに指定出来るようにしたい。

    $invoker->ignore(E_DEPRECATED)->getcwd();

`ignore()` で `new self(...)` などとするとパーシャルモックで不都合がある。

`ignore()` でうすーいラッパを用意して、`invokeArgsWithIgnore()` みたいなのを呼ぶようにする？
 → だめ、`$invoker` のマジックメソッドを呼ばないとモックにならない。

`ignore()` で返るうす～いラッパが $invoker に set/unset する？

    $level = $invoker->setIgnore($level);
    $retval = $invoker->getcwd();
    $invoker->setIgnore($level);
    return $retval;

`new self(...)` じゃなくで clone にする？ → コスト高そう・・・

### メソッド名を英語的に自然にする

```
value be not empty.     値は空ではないこと。
value be be resource.   値は、リソースであること。
value be resource.      値は、リソースである。

value is not empty.     値が空ではありません。
value is be resource.   値は、リソースである。

value be is not empty.  値が空でないこと。
value be not empty.     値は空ではないこと。
value be is resource.   値は、リソースであること。

value should not be empty.  値は空であってはならない。
value should be resource.   値は、リソースである必要があります。


```

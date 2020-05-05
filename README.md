# Laravel-Html2Pdf


[![Latest Stable Version](https://poser.pugx.org/oyswonder/html2pdf/v/stable)](https://packagist.org/packages/oyswonder/html2pdf)
[![Total Downloads](https://poser.pugx.org/oyswonder/html2pdf/downloads)](https://packagist.org/packages/oyswonder/html2pdf)
[![Latest Unstable Version](https://poser.pugx.org/oyswonder/html2pdf/v/unstable)](https://packagist.org/packages/oyswonder/html2pdf)
[![License](https://poser.pugx.org/oyswonder/html2pdf/license)](https://packagist.org/packages/oyswonder/html2pdf)



> 一个简单的包，用于把HTML页面生成为PDF文档。该程序包专门用于laravel，但您可以在不使用laravel的情况下使用。
> 此包是基于 [nahidulhasan/laravel-pdf](https://github.com/nahidulhasan/laravel-pdf) 上修改的。

## 安装

#### 安装 wkhtmltopdf 

经以下系统测试:

- Ubuntu 18.04.3 x64
- Windows 10 x64

```sh
sudo apt-get update
sudo apt-get install xvfb libfontconfig wkhtmltopdf
```

#### For docker 
```
RUN apt-get update && apt-get install xvfb libfontconfig wkhtmltopdf
```

#### Windows 可在官网直接下载安装包安装
https://wkhtmltopdf.org/downloads.html

#### Upddate Composer
```
composer require oyswonder/html2pdf
```

如果 laravel 版本<5.5，请将 ServiceProvider 添加到 config/app.php 中的 providers 数组中

    oyswonder\Html2pdf\Html2pdfServiceProvider::class,

您可以选择将 facade 用于较短的代码。把这个加到你的 facades 上:

    'Pdf'  => oyswonder\Html2pdf\Facades\Pdf::class,

## 基础用法

要创建PDF，请向您的一个 Controller 中添加类似的内容。

```php
use oyswonder\Html2pdf\Facades\Pdf;

$document = Pdf::generatePdf('http://bantouren.com/html/table/sample.html');

```

### 下载 pdf

将PDF保存到特定文件夹中的文件中，然后下载

``` 
use oyswonder\Html2pdf\Pdf;

$obj = new Pdf();

$url = 'http://bantouren.com/html/table/sample.html';

$invoice = $obj->generatePdf($url);

define('INVOICE_DIR', public_path('uploads/invoices'));

if (!is_dir(INVOICE_DIR)) {
    mkdir(INVOICE_DIR, 0755, true);
}

$outputName = str_random(10);
$pdfPath = INVOICE_DIR.'/'.$outputName.'.pdf';


File::put($pdfPath, $invoice);

$headers = [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' =>  'attachment; filename="'.'filename.pdf'.'"',
];

return response()->download($pdfPath, 'filename.pdf', $headers);

```

### Other Usage 

也可以使用以下方法 :

``` pdf::stream('http://bantouren.com/html/table/sample.html')  ```  在浏览器中打开PDF文件 


### Running without Laravel

您可以不在 Laravel 上使用此库。

例如:

```
use oyswonder\Html2pdf\Pdf;

$obj = new Pdf();
$document = $obj->generatePdf('http://bantouren.com/html/table/sample.html');
```

### License

Html2PDF for Laravel 是根据 [MIT license](http://opensource.org/licenses/MIT) 许可证许可的。

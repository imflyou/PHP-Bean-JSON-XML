This library has similar features of gson/jackson. Its purpose is to easily handle the conversion between PHP objects and JSON or XML.

## 主要功能
- 对象与JSON/XML的转换：自动将PHP对象转换为JSON字符串或XML字符串，反之亦然，减少了手动编写转换代码的工作量。
- 类型提示：在 PhpStorm 中支持代码提示会有代码提示
- 数据验证：内置多种数据验证器（如AssertFalse、AssertTrue、Future、Length等），在对象序列化和反序列化过程中自动进行数据验证，确保数据的有效性和完整性。
- 属性别名：支持为类属性定义别名，使得JSON/XML字段名与类属性名可以不同，增加了灵活性。
- 列表属性类型：通过注解明确数组属性的元素类型和维度，确保在序列化和反序列化过程中进行类型检查，避免数据类型不一致的问题。
## 优点
- 简化开发：通过自动化的对象与JSON/XML转换和数据验证，减少了手动编写和维护转换代码的工作量，提高了开发效率。
- 类型安全：在转换过程中，确保数据类型的一致性，避免类型错误。
- 灵活性：支持属性别名和列表属性类型，增加了处理复杂数据结构的灵活性。
- 易于扩展和维护：项目结构清晰，代码模块化，易于扩展和维护。
## 适用场景
- API开发：在开发RESTful API时，频繁需要将请求数据转换为对象，或将对象转换为响应数据，这个库可以大大简化这些操作。
- 数据交换：在不同系统之间进行数据交换时，确保数据格式和类型的一致性。
- 配置管理：处理复杂的配置文件，将配置文件内容转换为对象，便于管理和使用。

## PhpStorm 类型提示示例
- 原始数据类型

  
    ![image](https://github.com/user-attachments/assets/52ae33d2-484d-42b2-9505-efc39821285b)
- 反序列化的结果有类型，和传入的类类型一致
    ![image](https://github.com/user-attachments/assets/85d6c1e8-0a3b-4d37-9223-51ea284ec31a)

- 能识别其中嵌套的类的成员类型、编码时有代码提示
    ![image](https://github.com/user-attachments/assets/8f981617-ae18-4c75-a77e-04b0755f6076)


## 使用示例

### 类成员为基础数据类型

```php
class SimpleMapBean
{
    public ?string $vNull = null;
    public ?string $vString = '';
    public ?bool $vBool = false;
    public ?bool $vTrue = true;
    public ?bool $vFalse = false;
    public ?bool $vBoolean = true;
    public ?int $vInt = 10;
    public ?int $vInteger = -1;
    public ?float $vFloat = 1.234;
    public ?float $vDouble = 1.3456789;
    public ?array $vArray = [1, 2, 3];
    public ?object $vObject = null;
    public ?stdClass $vStdClass = null;
}

$jsonStr =<<<JSON
{"vNull":null,"vString":"","vBool":false,"vTrue":true,"vFalse":false,"vBoolean":true,"vInt":10,"vInteger":-1,"vFloat":1.234,"vDouble":1.3456789,"vArray":[1,2,3],"vObject":{"a":"a","b":"b"},"vStdClass":{"m1":"m1","m2":"m2"}}
JSON;

// simpleMapObject 数据类型为 SimpleMapBean，并且 PHPStorm 中会有相应的代码提示，能识别到数据类型 
$simpleMapObject = JSON::parseObj($jsonString, SimpleMapBean::class);
```

### 类成员为自定义的类

```php
class OrderBean
{
    public string $orderNo;
    public OrderInfoBean $orderInfo;

    /**
     * 此处通过注释写明数据类型，有利于 PHPStorm 分析数据类型
     * @var $goodsList GoodsInfoBean[]
     * 使用注解 ListPropertyType 明确数组内的数据类型
     */
    #[ListPropertyType(GoodsInfoBean::class)]
    public array $goodsList;
}

class OrderInfoBean
{
    public ?int $goodsCount;
    public ?bool $isCod;
    public ?float $amount;
    public ?string $ownerNo;
}

class GoodsInfoBean
{
    public ?string $specNo;
    public ?float $goodsCount;
}

$jsonStr = <<<JSON
{"orderNo":"orderNo:12345","orderInfo":{"goodsCount":123,"isCod":true,"amount":1.2345,"ownerNo":"ownerNo"},"goodsList":[{"specNo":"specNo","goodsCount":123},{"specNo":"specNo","goodsCount":123}]}
JSON;
$orderBean = JSON::parseObj($jsonString, OrderBean::class);
```

### 解析 JSON List 数据（“[{}]”）

```php
class SimpleMapBean
{
    public ?string $vNull = null;
    public ?string $vString = '';
    public ?bool $vBool = false;
    public ?bool $vTrue = true;
    public ?bool $vFalse = false;
    public ?bool $vBoolean = true;
    public ?int $vInt = 10;
    public ?int $vInteger = -1;
    public ?float $vFloat = 1.234;
    public ?float $vDouble = 1.3456789;
    public ?array $vArray = [1, 2, 3];
    public ?object $vObject = null;
    public ?stdClass $vStdClass = null;
}

$jsonString = <<<JSON
[{"vNull":null,"vString":"","vBool":false,"vTrue":true,"vFalse":false,"vBoolean":true,"vInt":10,"vInteger":-1,"vFloat":1.234,"vDouble":1.3456789,"vArray":[1,2,3],"vObject":{"a":"a","b":"b"},"vStdClass":{"m1":"m1","m2":"m2"}},{"vNull":null,"vString":"","vBool":false,"vTrue":true,"vFalse":false,"vBoolean":true,"vInt":10,"vInteger":-1,"vFloat":1.234,"vDouble":1.3456789,"vArray":[1,2,3],"vObject":{"a":"a","b":"b"},"vStdClass":{"m1":"m1","m2":"m2"}}]
JSON;

$parseList = JSON::parseList($jsonString, SimpleMapBean::class);
```

### 使用注解声明类成员别名

```php
class PropertyAliasBean
{
    #[PropertyAlias("spec_no")]
    public ?string $specNo;
    #[PropertyAlias("goods_count")]
    public ?float $goodsCount;
}

$jsonStr = <<<JSON
{"spec_no":"spec_no","goods_count":123}
JSON;

$propertyAliasBean = JSON::parseObj($jsonString, PropertyAliasBean::class);
```

### 使用数据校验器 Validator 在序列化同时校验数据

```php
class ValidationTestBean
{
    #[AssertFalse]
    public bool $assertFalse = false;
    #[AssertTrue]
    public bool $assertTrue = true;
    #[Future]
    public string $future = "9999-02-23 15:10:23";
    #[Length(10)]
    public string $length = "123456789";
    #[MustNotNull]
    public string $mustNotNull = "111";
    #[MustNull]
    public ?string $mustNull = null;
    #[NotBlank]
    public string $notBlank = "12345";
    #[Past]
    public string $past = "2000-02-23 15:11:20";
    #[Pattern("/\d{11}/")]
    public string $pattern = "01234567891";
}
```

```php
class ListPropertyTypeBean
{
    /**
     * 1 dimensional array
     *
     * @var int[]
     */
    #[ListPropertyType(TypeName::INT)]
    public array $oneArray = [1, 2, 3];

    /**
     * 2 dimensional array
     *
     * @var int[][]
     */
    #[ListPropertyType(TypeName::INT, 2)]
    public array $twoDimensionalArray = [
        [1, 2, 3],
        [4, 5, 6],
    ];
}

$jsonString = <<<JSON
{"oneArray":[1,2,3],"twoDimensionalArray":[[1,2,3],[4,5,6]]}
JSON;
$parseObj = JSON::parseObj($jsonString, ListPropertyTypeBean::class);
```

- The getter/setter name should be 'camelCase'; support set{camelCase}, get{camelCase}, is{camelCase} methods.
- 建议都加上默认值，或者有 getter 方法，内部用 isset() 或者 ?? 处理，返回默认值，PHP8 要求对象必须初始化（initialized）后才能使用
- 所有的对象嵌套，都允许为 null，便于使用
- bean class 不能有含参数的构造函数
- 考虑字段不传的情况，需要支持 null，比如更新类型的接口
- 不能显示声明构造函数，或者只能有非必填参数的构造函数
- 都加上 ? 声明
- 别名（alias）：优先级低。

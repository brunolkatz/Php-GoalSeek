# Php-GoalSeek

- Php function to simulate Goal Seek from libre office using the Regula Falsi method.
- Install
 ```bash
 composer require kidjapa/php-goalseek
 ```


## In Libre office

- Function in archive: core/sc/source/coredata/dcoumen4.cxx

```c++
bool ScDocument::Solver(SCCOL nFCol, SCROW nFRow, SCTAB nFTab,
                        SCCOL nVCol, SCROW nVRow, SCTAB nVTab,
                        const OUString& sValStr, 
                        double& nX
                        )
```

## Sample

```php
$goalSeek = new SolveGoalSeek();

$getValue = 0;
$getValue = $goalSeek->seekGoal(
    function($value, $data){
        return sqrt($value);
    },
    16,
    20
);

echo "------------- results ------------- \n";
echo "Result: ".$getValue."\n"; // Expect: 400
```

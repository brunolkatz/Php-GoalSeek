# PhpGoalSeek

- Php function to simulate Goal Seek from libre office using the Regula Falsi method.


## In Libre office

- Function in archive: core/sc/source/coredata/dcoumen4.cxx

```c++
bool ScDocument::Solver(SCCOL nFCol, SCROW nFRow, SCTAB nFTab,
                        SCCOL nVCol, SCROW nVRow, SCTAB nVTab,
                        const OUString& sValStr, 
                        double& nX
                        )
```


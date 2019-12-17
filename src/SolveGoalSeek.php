<?php

namespace Kidjapa;

class SolveGoalSeek{

    const M_PI_2 = 1.57079632679489661923;

    /**
     * @param callable $aFormula Function with calc
     * @param integer|float $actualValue Actual value
     * @param integer|float $aGoalValue Value expected
     * @param array $additionalCallData Array with additional data to formula calc
     * @return float
     * @throws \Exception
     */
    public function seekGoal($aFormula,$actualValue,$aGoalValue, $additionalCallData = null){
        $fValue = 0.0;
        $bFound = $this->Solver(
            $aFormula,
            $actualValue,
            $aGoalValue,
            $fValue,
            $additionalCallData);

        if(!$bFound)
            return 0.0;
        return $fValue;
    }

    /**
     *   (Goal Seek) Find a value of x that is a root of f(x)
     *
     * @param callable $nF value, a formula with simple value to get returned value
     * @param double|integer $nV actual value
     * @param double|integer $gV Goal Value I need
     * @param double $nX returned calculed value
     * @param null $additionalData
     * @return bool
     * @throws \Exception
     */
    private function Solver($nF, $nV, &$gV, &$nX, $additionalData = null){

        $bRet = false; // OK

        $fTargetVal = $gV;

        $nX = 0.0;

        $bDoneIteration = false;

        // Original value to be restored later if necessary
        $fSaveVal = $nV;

        $nMaxIter = 100; // Number max of interactions

        $fEps =   floatval(1e-10);
        $fDelta = floatval(1e-6);

        $fBestX = null; // best x axis prev
        $fXPrev = null; // x axis prev
        $fBestF = null; // best formula
        $fFPrev = null; // Formula prev
        $fBestX = $fXPrev = $fSaveVal;

        // fFprev: RAIZ(16) === 4 => 4 - 20 => -16
        $fFPrev = $nF($nV, $additionalData) - $fTargetVal; // Calculate a prev formula


        // Set the first best value with the result of actual value sett
        $fBestF = abs( $fFPrev );

        // If best value is below of $fDelta then continue to find the real value
        if ( $fBestF < $fDelta )
            $bDoneIteration = true;


        // 16+(floatval(1e-10)) -> 1e-16
        $fX = $fXPrev + $fEps;
        $fF = $fFPrev;  // saved prev value formula
        //$fSlope = 0.0;

        $nIter = 0; // set init counter

        $bHorMoveError = false;


        while ( !$bDoneIteration && ( $nIter++ < $nMaxIter ) ){

            $nV = $fX;

            $fF = $nF($nV, $additionalData) - $fTargetVal;

            if ( $fF == $fFPrev){ // Se os valores forem iguais aos calculados originalmente

                $nHorIter = 0;

                $fHorStepAngle = 5.0;
                $fHorMaxAngle = 80.4;
                $nHorMaxIter = (int) abs($fHorMaxAngle / $fHorStepAngle);

                $bDoneHorMove = false;

                while ( !$bDoneHorMove && !$bHorMoveError && $nHorIter++ < $nHorMaxIter )
                {

                    $fHorAngle = $fHorStepAngle * ((double)$nHorIter);

                    $fHorTangent = tan($this->deg2rad($fHorAngle));

                    $nIdx = 0;

                    while( $nIdx++ < 2 && !$bDoneHorMove )
                    {
                        $fHorX = null;
                        if ( $nIdx == 1 )
                            $fHorX = $fX + abs( $fF ) * $fHorTangent;
                        else
                            $fHorX = $fX - abs( $fF ) * $fHorTangent;

                        $nV = $fHorX;

                        $fF = $nF($nV, $additionalData) - $fTargetVal;
                        if ( $fF != $fFPrev )
                        {
                            $fX = $fHorX;
                            $bDoneHorMove = true;
                        }
                    }
                    if ( !$bDoneHorMove )
                        $bHorMoveError = true;

                }

            } // end if $f === $fFPrev

            if ( $bHorMoveError )
                break;
            else if(abs($fF) < $fDelta){
                // converged to root
                $fBestX = $fX;
                $bDoneIteration = true;
            }else{

                if ( (abs($fF) + $fDelta) < $fBestF )
                {
                    $fBestX = $fX;
                    $fBestF = abs( $fF );
                }

                if ( ( $fXPrev - $fX ) != 0 )
                {
                    $fSlope = ( $fFPrev - $fF ) / ( $fXPrev - $fX );
                    if ( abs( $fSlope ) < $fEps )
                        $fSlope = $fSlope < 0.0 ? -$fEps : $fEps;
                }
                else
                    $fSlope = $fEps;

                $fXPrev = $fX;
                $fFPrev = $fF;
                $fX = $fX - ( $fF / $fSlope );

            }

        } // End while

        // Try a nice rounded input value if possible.
        $fNiceDelta = ( $bDoneIteration && abs( $fBestX ) >= 1e-3 ? 1e-3 : $fDelta );

        $nX = $this->approxFloor( ( $fBestX / $fNiceDelta ) + 0.5 ) * $fNiceDelta;

        if ( $bDoneIteration )
        {
            $nV = $nX;
            if ( abs( $nF($nV, $additionalData) - $fTargetVal ) > abs( $fF ) )
                $nX = $fBestX;
            $bRet = true;
        }else if ( $bHorMoveError )
        {
            $nX = $fBestX;
        }

        //$nV = $fSaveVal;
        if ( !$bDoneIteration )
        {
            throw new \Exception("Interpreter: NA() not available condition, not a real error", 1);
        }

        return $bRet;

    } // End function

    private function deg2rad($v){
        return $v / 90.0 * self::M_PI_2;
    }

    private function approxFloor($a)
    {
        return floor(round($a,15));
    }

}

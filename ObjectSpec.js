// Import the main jsPsych object
const jsPsych = initJsPsych({
    on_finish: function() {
        WriteTaskIO().then(function(P1) {
            TargetUrl = P1.TargetUrl;
            window.location.replace(TargetUrl);
        }).catch(function(Err) {
            alert('An error has occurred.\nPlease report error code #200 to the experimenter:\n'+Err);
            window.location.replace("./Error.html");
        });
    }
});

// Set some global variables that are available in all functions
// High-level global variables
var Assignment;
var Pairs = [];
var TimelineVars;

// Trial specific global variables
var PairId = null;
var PosOnRight = null;
var StartTimeOfTrial = null;
var SelectedRight = null;
var Correct = null;
var RT = null;
var ResponseMade = false;

// Specify the preload event
var ImgsToPreload = [
    './Imgs/l00.png',
    './Imgs/l01.png',
    './Imgs/l02.png',
    './Imgs/l03.png',
    './Imgs/l04.png',
    './Imgs/l05.png',
    './Imgs/h00.png',
    './Imgs/h01.png',
    './Imgs/h02.png',
    './Imgs/h03.png',
    './Imgs/h04.png',
    './Imgs/h05.png'
    ];
var PreloadImgs = {
    type: jsPsychPreload,
    images: ImgsToPreload
};

// Specify the ExitFullscreen event
var ExitFullscreen = {
    type: jsPsychFullscreen,
    fullscreen_mode: false,
    on_finish: function() {EnforceUnfocus = false;}
};

// Specify the Fixation event
var Fixation = {
    type: jsPsychHtmlButtonResponse,
    stimulus: '<p><font color="#000000" size="60px">+</font></p>',
    choices: [],
    prompt: "",
    trial_duration: 1000,
    
    // Reset all the global variables (on_start is called at the beginning of the trial)
    on_start: function() {
        PairId = jsPsych.timelineVariable('PairId');
        PosOnRight = jsPsych.timelineVariable('PosOnRight');
        ResponseMade = false;
        SelectedRight = null;
        Correct = null;
        RT = null;
    }
};

// Specify the Trial event
var Trial = {
    
    // Set the start time of this trial
    on_start: function() {
        StartTimeOfTrial = jsPsych.getTotalTime();
    },
    
    // Set the type of this trial
    type: jsPsychHtmlButtonResponse,
    
    // Set the stimuli to display
    stimulus: function(){
        // Specify the individual part of the stimulus HTML string
        var Part1 = '<img src="';
        var Part3 = `" width="${ImgWidth0}px" style="vertical-align:middle;margin:0px 60px" id="ImgLeft" onclick="javascript:ImgClicked(this.id)"> <img src="`;
        var Part5 = `" width="${ImgWidth0}px" style="vertical-align:middle;margin:0px 60px" id="ImgRight" onclick="javascript:ImgClicked(this.id)">`;
        var Pos = jsPsych.timelineVariable('Pos');
        var Neg = jsPsych.timelineVariable('Neg');
        
        // Construct the StimString based on the value of PosOnRight
        if (jsPsych.timelineVariable('PosOnRight')){
            var StimString = Part1+Neg+Part3+Pos+Part5;
        } else {
            var StimString = Part1+Pos+Part3+Neg+Part5;
        }
        return StimString;
        },
    
    // Set the choices and prompt fields to be empty (we don't use them)
    choices: [],
    prompt: "",
    
    // Set the trial duration
    trial_duration: 4000,
    
    // At the end of the trial, push the results into TaskIO.Trials
    on_finish: function() {
        var TrialObj = {
            PairId: PairId,
            PosOnRight: PosOnRight,
            ResponseMade: ResponseMade,
            Correct: Correct,
            RT: RT
        };
        TaskIO.Trials.push(TrialObj);
    }
};

// Specify the Feedback event
var Feedback = {
    type: jsPsychHtmlButtonResponse,
    
    // Set the feedback to display
    stimulus: function() {
        if (ResponseMade) {
            if (Correct) {
                return '<p style="vertical-align:middle; margin: 0px 5px 30px; color:#00ff00; font-size:100px">&#10003;</p>';
            } else {
                return '<p style="vertical-align:middle; margin: 0px 5px 30px; color:#ff0000; font-size:100px">&#10060;</p>';
            }
        } else {
            return '<p style="vertical-align:middle;color:#ff0000;font-size:40px">Please try to respond on time!</p>';
        }
    },
    
    // Set the choices and prompt fields to be empty (we don't use them)
    choices: [],
    prompt: "",
    
    // Set the feedback duration (depends on whether a response was made)
    trial_duration: function() {
        if (ResponseMade) {
            return 3000;
        } else {
            return 5000;
        }
    }
};
import pandas as pd
from pathlib import Path
import random 
import string
import numpy as np



class Student:

    def __init__(self, comments: str, dates: str, main: str,courses:str):
        self.comments = comments  # more then 7m line
        self.dates = dates  # 2500 line
        self.main = main  # 5000 line
        self.courses=courses

    @property
    def courses(self):
        return self._courses

    @courses.setter
    def courses(self,courses):
        file_path :Path = Path(courses)
        if file_path.exists():
            self._courses=courses
        else:
            raise ValueError(f'"{courses}" path is incorrect.')
    
    
    @property
    def comments(self):
        return self._comments

    @comments.setter
    def comments(self, comments):
        file_path: Path = Path(comments)
        if file_path.exists():
            self._comments = comments
        else:
            raise ValueError(f"'{comments}' is incorrect path.")

    @property
    def dates(self):
        return self._dates

    @dates.setter
    def dates(self, dates):
        file_path: Path = Path(dates)
        if file_path.exists():
            self._dates = dates
        else:
            raise ValueError(f'"{dates}" is incorrect path,')

    @property
    def main(self):
        return self._main

    @main.setter
    def main(self, main):
        file_path = Path(main)
        if file_path.exists():
            self._main = main
        else:
            raise ValueError(f'"{main}" path is incorrect.')

    def extract_comments(self,length:int) ->pd.DataFrame:
        """
        this function will read dates.csv file then it will extract comments, clean them.

        :return: this function will new dataframe has only comment column
        :rtype: pandas.DataFrame

        """
        df: pd.DataFrame = pd.read_csv(self.comments)
        df=df.drop(columns=["id", "course_id", "rate", "date", "display_name"])
        df=df.drop_duplicates()
        new_df:pd.DataFrame = df.iloc[[i for i in range(3*length)]]

        c:int = 0
        array:list = []
        comments:str = ""
        for i in range(3*length):
            if c%3!=0 or c==0:
                comments+=""+new_df.iloc[i]["comment"]+"\n"
            elif c%3 ==0:
                array.append(comments)
                comments=""
            c+=1
        result:pd.DataFrame = pd.DataFrame({"comment":array})

        result.to_csv("result/"+self.comments,index=False)
        return result

    def extract_dates(self) ->pd.DataFrame:
        """
        this function will read dates.csv file then it will extract the date of signup and last seen, clean them.

        :return: this function will return new cleaned dataframe
        :rtype: pandas.Dataframe

        """
        df: pd.DataFrame = pd.read_csv(self.dates)
        df=df.drop(
            columns=[
                "UserID",
                "CourseName",
                "SessionDuration",
                "SessionsPerWeek",
                "CourseCompletion",
                "UserSatisfaction",
                "QuizScores",
                "FeedbackComments",
            ]
        )
        df.to_csv("result/"+self.dates,index=False)
        return df
    def generate_password(self,length:int=10) ->str:
        chars = string.ascii_letters + string.digits + "!@#$%&*"
        return ''.join(random.choice(chars) for _ in range(length))

    def extract_student_info(self) ->pd.DataFrame:
        """
        this funtion will read main.csv file and extract the important information for the students

        :return: this function will return new dataframe
        :rtype: pandas.DataFrame
        """

        df: pd.DataFrame = pd.read_csv(self.main)
        df=df.drop(
            columns=[
                "Attendance (%)",
                "Midterm_Score",
                "Final_Score",
                "Assignments_Avg",
                "Quizzes_Avg",
                "Participation_Score",
                "Projects_Score",
                "Total_Score",
                "Grade",
                "Study_Hours_per_Week",
                "Extracurricular_Activities",
                "Internet_Access_at_Home",
                "Parent_Education_Level",
                "Family_Income_Level",
                "Stress_Level (1-10)",
                "Sleep_Hours_per_Night",
            ]
        )
        new_df :pd.DataFrame = df.iloc[[i for i in range(2500)]]
        new_df.to_csv("result/"+self.main,index=False)
        return new_df
    
    def generate_courses(self,df:pd.DataFrame,length:int) ->pd.DataFrame:
        """
        this function generate dataframe when each line has 3 course name

        :param df: this is dataframe that has student information
        :type df: pandas.DataFrame

        :return: return new dataframe that has one column, each line has three name of courses
        :rtype: pandas.DataFrame
        """
        array:list[str] = []
        index:list[int] = [i for i in range(15)]
        index:list[int] = random.sample(index,4)

        total_courses:pd.DataFrame = pd.read_csv(self.courses)

        for i in range(length):
            index:list[int] = [i for i in range(15)]
            index:list[int] = random.sample(index,4)
            department:str =df.loc[i,"Department"]
            course :pd.DataFrame = total_courses[total_courses["Department"]==department]

            result:str = ""
            for j in range (4):
                result +=str(course.iloc[index[j]]["CourseName"])+"\n"
            array.append(result)
            
        pd.DataFrame({"course_name":array}).to_csv("result/"+self.courses)
        return pd.DataFrame({"course_name":array})
    
    def merge_result(self) ->pd.DataFrame:
        """
        this function will merge all cleaned datasets at one.

        :return: will return new dataframe
        :rtype: pandas.DataFrame
        """

        info :pd.DataFrame = self.extract_student_info().reset_index(drop=True)
        length = len(info)
        comments:pd.DataFrame = self.extract_comments(length).reset_index(drop=True)
        dates:pd.DataFrame = self.extract_dates().reset_index(drop=True)
        courses:pd.DataFrame = self.generate_courses(info,length).reset_index(drop=True)
        
        #add passoword
        info["password"] = [self.generate_password() for _ in range(length)]

        result:pd.DataFrame = pd.concat([info,courses,dates,comments],axis=1)
        result.columns = result.columns.str.lower()
        result.rename(columns={"signupdate":"signup_date","lastactivedate":"last_active_date"},inplace=True)
        result.to_csv("result/student.csv",index=False)
        return result
    
    @staticmethod
    def add_quiz_percent(file_path:str):
        """
        this function add quiz score to the student dataset

        :param file_path: refers to the path of student dataset
        :type file_path: str

        :return: return new dataframe
        :rtype: pandas.DataFrame
        
        """
        file:Path = Path(file_path)
        if not file.exists():
            raise ValueError(f'"{file_path}" path is not exist.')
        df:pd.DataFrame = pd.read_csv(file_path)
        length = len(df)
        percent:list[float]=np.random.rand(length)

        df_quize:pd.DataFrame = pd.DataFrame({"quiz_score":percent})
        new_df: pd.DataFrame = pd.concat([df,df_quize],axis=1)
        new_df.to_csv(file_path,index=False)

        return new_df

        
        

"""
last result:
Index(['student_id', 'first_name', 'last_name', 'email', 'gender', 'age',
       'department', 'password', 'course_name', 'signup_date',
       'last_active_date', 'comment', 'quiz_score'],
      dtype='str')
"""
if __name__ == "__main__":
    try:
        student =Student("comments.csv","dates.csv","main.csv","courses.csv")
        print(student.merge_result())
    except ValueError :
        new_df:pd.DataFrame=Student.add_quiz_percent("student.csv")
        print(new_df.columns)

    


import pandas as pd
import string
import random
from pathlib import Path


class Professor:
    def __init__(self, professor: str):
        self.professor = professor  # 20 000 line

    @property
    def professor(self):
        return self._professor

    @professor.setter
    def professor(self, professor):
        file_path: Path = Path(professor)
        if file_path.exists():
            self._professor = professor
        else:
            raise ValueError(f'"{professor}" path is incorrect')

    def generate_password(self, length: int = 10) -> str:
        """
        this function will generate a password

        :param length: refers to password length
        :type length: int

        :return: this function will return generated password
        :rtype: str

        """
        chars = string.ascii_letters + string.digits + "!@#$%&*"
        return "".join(random.choice(chars) for _ in range(length))

    def generate_ids(self, length: int) -> pd.DataFrame:
        """
        this function will generate id for each professor

        :param length: refer to professor number
        :type length: int

        :return: will return dataframe that contains id column
        :rtype: pandas.DataFrame

        """
        ids: list[int] = [i for i in range(length)]
        df: pd.DataFrame = pd.DataFrame({"id": ids}).reset_index(drop=True)
        df.to_csv("result/ids.csv",index=False)
        return df

    def generate_professor_info(self) -> pd.DataFrame:
        """
        this function will read dataset, clean it, extract importent infromation, apply normalization

        :return: will return new cleaned dataset
        :rtype: pandas.DataFrame

        """
        df = pd.read_csv(self.professor)
        df =  df[["professor_name", "department_name"]].copy().drop_duplicates().reset_index(drop=True)
        

        filter_values = [
            "Business department",
            "Computer Science department",
            "Mathematics department",
            "Engineering department",
        ]

        df = df[df["department_name"].isin(filter_values)].reset_index(drop=True)

        df["department_name"] = df["department_name"].replace(
            {
                "Business department": "Business",
                "Computer Science department": "CS",
                "Mathematics department": "Mathematics",
                "Engineering department": "Engineering",
            }
        )
        df.rename(
            columns={"professor_name": "name", "department_name": "department"},
            inplace=True,
        )
        df.to_csv("result/info.csv", index=False)
        return df

    def merge_results(self):
        """
        this function will merge two cleaned dataset at one

        :return: new cleaned dataset that containe professor id, name ,department, password
        :rtype: pandas.DataFrame

        """
        professor: pd.DataFrame = self.generate_professor_info().reset_index(drop=True)
        length = len(professor)
        ids: pd.DataFrame = self.generate_ids(length=length)
        passwords: pd.DataFrame = pd.DataFrame(
            {
                "password": [self.generate_password() for _ in range(length)],
            }
        )

        new_df: pd.DataFrame = pd.concat([ids, professor, passwords], axis=1)
        new_df.to_csv("result/professor.csv", index=False)
        return new_df

# last result is Index(['id', 'name', 'department', 'password'], dtype='str')
if __name__ == "__main__":
    professor = Professor("professor.csv")
    df = professor.merge_results()
    print(df.columns)
